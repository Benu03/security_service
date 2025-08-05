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


class MainController extends Controller
{

    public function profile(Request $request)
    {
            

        $data = [   'title' => 'Dashboard',
                    'user' =>  Session::get('user_module'),
                    'module' => Session::get('modules'),
                    'content'   => 'dasboard/bengkel'
                ];
        return view('layout/wrapper',$data);

    }


    public function logout(Request $request) {

        $body = [
            'session_token' => session()->get('user_module')['session_token'],
            'username'      => session()->get('user_module')['username']
        ];

        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $encryptionKey = config('static.key_access') . $timestamp;
        $key = hash(config('static.key_hash'), $encryptionKey);
        $responseSession = Http::withHeaders([
            'Content-Type' => 'application/json',
            'key-service' => $key,
            'timestamp' => $timestamp
        ])->withoutVerifying()->post( config('static.url_access').'/auth/logout', $body);

        $responseSessionData = json_decode($responseSession ,true);

        if($responseSessionData['status'] == 200){
            session()->flush();
            return redirect(config('static.url_portal_ts3_main').'login');
        }
         

    }


    public function lobby(Request $request) {
        
        session()->flush();
        return redirect(config('static.url_portal_ts3_main').'lobby');

    }


    
    public function getNotifications()
    {
        
        $username = session()->get('user_module')['username'];
        $oneWeekAgo = Carbon::now()->subWeek();
        $notifications = DB::connection('sso')->table('ntf.v_ntf_notification_list')
        ->where('username',$username)
        ->where('module','MOTOR SERVICE')
        ->where('created_date', '>=', $oneWeekAgo)
        ->orderBy('created_date', 'desc')->get();
        return response()->json($notifications);
    }

    public function updateNotifIsread(Request $request)
    {
        $notifId = $request->input('notif_id');
        $username = session()->get('user_module')['username'];
    
        $alreadyExists = DB::connection('sso')->table('ntf.ntf_notification_read')
            ->where('ntf_notification_id', $notifId)
            ->where('username', $username)
            ->exists();
    
        if (!$alreadyExists) {

            DB::connection('sso')->table('ntf.ntf_notification_read')->insert([
                'is_read' => true,
                'ntf_notification_id' => $notifId,
                'username' => $username,
                'created_date' => Carbon::now()
            ]);
        }
    
        return response()->json(['success' => true]);
    }




   




}
