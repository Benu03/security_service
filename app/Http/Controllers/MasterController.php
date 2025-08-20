<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class MasterController extends Controller
{

        public function UsersAccess(Request $request)
        {
                
                $role = Session::get('modules')['role'] ?? null;
                if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {

                        $cust =  DB::connection('sso')
                        ->table('mst.mst_customer')
                        ->orderBy('created_date', 'desc')->get();


                        $count = DB::connection('scr')
                        ->table('mst.mst_users_access')
                        ->whereNull('deleted_date')->count();

                        $data = [
                                'title' => 'Master Users Access',
                                'content' => 'master/user_access',
                                'customers' => $cust,
                                 'count_cust' => $count,
                        ];
                        
                        return view('layout/wrapper', $data);
                        
                }
                
                $data = [   
                        'title' => 'Access Forbidden',
                        'content'   => 'global/notification/forbidden'
                ];

                return view('layout/wrapper',$data);
                
        }


        public function GetUsersAccess(Request $request)
        {

                if ($request->ajax()) {
                        $doc = DB::connection('scr')
                        ->table('mst.mst_users_access')
                        ->select('id','username', 'nik','fullname','customer','role', 'created_date') 
                        ->whereNull('deleted_date')
                        ->orderBy('created_date', 'desc');

    
                        return DataTables::of($doc)->make(true);
                }

                Log::warning('GetUsersAccess accessed without ajax', [
                        'url' => $request->fullUrl(),
                        'ip' => $request->ip()
                ]);

                abort(404);
        }
  
        public function EditUsersAccess(Request $request)
        {
                $user = Session::get('user_module');
                $role = Session::get('modules')['role'] ?? null;

                if (!in_array($role, ['ADMIN'])) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Access Forbidden: Anda tidak memiliki izin untuk menambah data ini.'
                        ], 403);
                }

                 DB::connection('scr')->beginTransaction();
                try {
                        
                     $dataUpdate = [
                                'customer'    => trim($request->customer),
                                'updated_by'       => $user['username'] ?? 'SYSTEM',
                                'updated_date'       => Carbon::now()->format('Y-m-d H:i:s')
                                ];

                        Log::info($dataUpdate);
                          Log::info($request->id);
                                        
                        DB::connection('scr')->table('mst.mst_users_access')
                        ->where('id', $request->id)
                        ->update($dataUpdate);

                        DB::connection('scr')->commit();
                        return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil di perbaharui.'
                        ]);
                } catch (\Exception $e) {
                        DB::connection('scr')->rollBack();
                        Log::error('Error AddReqDriver', ['message' => $e->getMessage()]);
                        return response()->json([
                        'success' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                        ], 500);
                }
               
                
        }


        public function JadwalPresensi(Request $request)
        {
                
                $role = Session::get('modules')['role'] ?? null;
                if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {
                        $data = [
                                'title' => 'Master Jadwal Presensi',
                                'content' => 'master/jadwal_presensi',
                        ];
                        
                        return view('layout/wrapper', $data);
                        
                }
                
                $data = [   
                        'title' => 'Access Forbidden',
                        'content'   => 'global/notification/forbidden'
                ];

                return view('layout/wrapper',$data);
                
        }

        public function TitikPatroli(Request $request)
        {
                
                $role = Session::get('modules')['role'] ?? null;
                if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {

                         $count = DB::connection('scr')
                                ->table('scr.scr_mst_customer_location')
                                ->select('category', DB::raw('COUNT(*) as total'))
                                ->whereIn('category', ['CHECK POINT', 'PRESENSI'])
                                ->whereNull('deleted_date')
                                ->groupBy('category')
                                ->get();

                        $cust =  DB::connection('sso')
                        ->table('mst.mst_customer')
                        ->orderBy('created_date', 'desc')->get();


                        $data = [
                                'title' => 'Master Titik Patroli',
                                'content' => 'master/titik_patroli',
                                 'customers' => $cust,
                                'count' => $count
                        ];
                        
                        return view('layout/wrapper', $data);
                        
                }
                
                $data = [   
                        'title' => 'Access Forbidden',
                        'content'   => 'global/notification/forbidden'
                ];

                return view('layout/wrapper',$data);
                
        }

        public function GetTitikPatroli(Request $request)
        {

                if ($request->ajax()) {
                        $doc = DB::connection('scr')
                        ->table('scr.scr_mst_customer_location')
                        ->whereNull('deleted_date')
                        ->orderBy('created_date', 'desc');
    
                        return DataTables::of($doc)->make(true);
                }

                Log::warning('GetTitikPatroli accessed without ajax', [
                        'url' => $request->fullUrl(),
                        'ip' => $request->ip()
                ]);

                abort(404);
        }

        public function AddTitikPatroli(Request $request)
        {
              
                $user = Session::get('user_module');
                $role = Session::get('modules')['role'] ?? null;

                if (!in_array($role, ['ADMIN'])) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Access Forbidden: Anda tidak memiliki izin untuk menambah data ini.'
                        ], 403);
                }

                 DB::connection('scr')->beginTransaction();
                try {

                          $seq = DB::connection('scr')
                                ->table('scr.scr_mst_customer_location')
                                ->where('customer_name', trim($request->customer))
                                ->where('category', trim($request->category))
                                ->max('seq');
                        $seq = $seq ? $seq + 1 : 1;

                        $code = $request->category === 'PRESENSI' ? null : 'CHK_' . now()->format('YmdHis');

                        $dataInsert = [
                                'customer_name'   => trim($request->customer),
                                'category'        => $request->category ? trim($request->category) : null,
                                'seq'             => $seq,
                                'latitude'        => $request->latitude ? trim($request->latitude) : null,
                                'longitude'       => $request->longitude ? trim($request->longitude) : null,
                                'radius'          => trim($request->radius),
                                'checkpoint_code' => $code,
                                'location'        => trim($request->location),
                                'created_by'      => $user['username'] ?? 'SYSTEM',
                        ];

                        DB::connection('scr')->table('scr.scr_mst_customer_location')->insert($dataInsert);

                        DB::connection('scr')->commit();

                        return response()->json([
                                'success' => true,
                                'message' => 'Data berhasil ditambahkan.'
                        ]);



                } catch (\Exception $e) {
                        DB::connection('wa')->rollBack();
                        Log::error('Error AddReqDriver', ['message' => $e->getMessage()]);
                        return response()->json([
                        'success' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                        ], 500);
                }


        }

        public function PrintQrTitikPatroli($data,Request $request)
        {
                $user = Session::get('user_module');
                $role = Session::get('modules')['role'] ?? null;

                if (!in_array($role, ['ADMIN'])) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Access Forbidden: Anda tidak memiliki izin untuk menambah data ini.'
                        ], 403);
                }

                $GetData =  DB::connection('scr')
                                ->table('scr.scr_mst_customer_location')->find($data);

                if (!$GetData) {
                return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.',
                ], 404);
                }


                $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($GetData->checkpoint_code));


                $pdf = Pdf::loadView('pdf.titik_patroli_qr', [
                        'data' => $GetData,
                        'qrCode' => $qrCode,
                ]);

                 return $pdf->stream("QR_TitikPatroli_{$GetData->checkpoint_code}.pdf");
                Log::info($GetData);

                

        }

        public function GetTitikPatroliDetail($id, Request $request)
        {
                $user = Session::get('user_module');
                $role = Session::get('modules')['role'] ?? null;

                if (!in_array($role, ['ADMIN'])) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Access Forbidden: Anda tidak memiliki izin untuk melihat data ini.'
                        ], 403);
                }

                $data = DB::connection('scr')
                                ->table('scr.scr_mst_customer_location')
                                ->where('id', $id)
                                ->first();

                if (!$data) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.',
                        ], 404);
                }

                return response()->json([
                        'success' => true,
                        'customer_name' => $data->customer_name,
                        'category' => $data->category,
                        'latitude' => $data->latitude,
                        'longitude' => $data->longitude,
                        'radius' => $data->radius,
                        'checkpoint_code' => $data->checkpoint_code,
                        'location' => $data->location,
                        'id_titik' => $data->id,
                ]);
        }

        public function EditTitikPatroli(Request $request)
        {
                $user = Session::get('user_module');
                $role = Session::get('modules')['role'] ?? null;

                if (!in_array($role, ['ADMIN'])) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Access Forbidden: Anda tidak memiliki izin untuk menambah data ini.'
                        ], 403);
                }

                 DB::connection('scr')->beginTransaction();
                try {
                        
                     $dataUpdate = [
                                'latitude'        => $request->latitude ? trim($request->latitude) : null,
                                'longitude'       => $request->longitude ? trim($request->longitude) : null,
                                'radius'          => trim($request->radius),
                                'location'        => trim($request->location),
                                'updated_by'       => $user['username'] ?? 'SYSTEM',
                                'updated_date'       => Carbon::now()->format('Y-m-d H:i:s')
                                ];

                        Log::info($dataUpdate);
                   
                                        
                        DB::connection('scr')->table('scr.scr_mst_customer_location')
                        ->where('id', $request->id)
                        ->update($dataUpdate);

                        DB::connection('scr')->commit();
                        return response()->json([
                        'success' => true,
                        'message' => 'Data berhasil di perbaharui.'
                        ]);
                } catch (\Exception $e) {
                        DB::connection('scr')->rollBack();
                        Log::error('Error AddReqDriver', ['message' => $e->getMessage()]);
                        return response()->json([
                        'success' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                        ], 500);
                }
               
                
        }

        public function DelTitikPatroli($id, Request $request)
        {
                $user = Session::get('user_module');
                $role = Session::get('modules')['role'] ?? null;

                if (!in_array($role, ['ADMIN'])) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Access Forbidden: Anda tidak memiliki izin untuk menghapus data ini.'
                        ], 403);
                }

                $GetData = DB::connection('scr')
                        ->table('scr.scr_mst_customer_location')
                        ->find($id);

                if (!$GetData) {
                        return response()->json([
                        'success' => false,
                        'message' => 'Data tidak ditemukan.',
                        ], 404);
                }

                try {
                        DB::connection('scr')->beginTransaction();

                        $dataUpdate = [
                        'deleted_by'   => $user['username'] ?? 'SYSTEM',
                        'deleted_date' => Carbon::now()->format('Y-m-d H:i:s')
                        ];

                        Log::info($dataUpdate);

                        DB::connection('scr')
                        ->table('scr.scr_mst_customer_location')
                        ->where('id', $id) // <-- gunakan $id, bukan $request->id
                        ->update($dataUpdate);

                        DB::connection('scr')->commit();

                        return response()->json([
                        'success' => true,
                        'message' => 'Data telah berhasil dihapus.',
                        ]);

                } catch (\Exception $e) {
                        DB::connection('scr')->rollBack();
                        return response()->json([
                        'success' => false,
                        'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                        ], 500);
                }
        }

        


        
        


}