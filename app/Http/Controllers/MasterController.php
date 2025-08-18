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

                if (!in_array($role, ['ADMIN', 'TMS'])) {
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

                //          $seq = check  scr.scr_mst_customer_location where customer_name dann category 
                //          max seq  + 1

                //          $code = CHK_TIMESTAP
                        
                //      $dataInsert = [
                //                 'customer_name'            => trim($request->customer),
                //                 'category'       => isset($request->category) ? trim($request->category) : null,
                //                 'seq'   => $seq,
                //                 'latitude' => isset($request->latitude) ? trim($request->latitude) : null,
                //                 'longitude'         => trim($request->longitude),
                //                 'radius'    => trim($request->radius),
                //                 'checkpoint_code'    => $code,
                //                 'location'    => trim($request->customer_list),
                //                 'created_by'       => $user['username'] ?? 'SYSTEM'
                //                 ];

                                Log::info($request);
                                        
                        // DB::connection('scr')->table('scr.scr_mst_customer_location')->insert($dataInsert);

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

        


}