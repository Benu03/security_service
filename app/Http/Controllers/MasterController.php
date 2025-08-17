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


class MasterController extends Controller
{

    public function UsersAccess(Request $request)
    {
        
        $role = Session::get('modules')['role'] ?? null;
        if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {
                 $data = [
                        'title' => 'Master Users Access',
                        'content' => 'master/user_access',
                ];
                
                return view('layout/wrapper', $data);
                
        }
        
        $data = [   
                'title' => 'Access Forbidden',
                'content'   => 'global/notification/forbidden'
        ];

        return view('layout/wrapper',$data);
        
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
                 $data = [
                        'title' => 'Master Titik Patroli',
                        'content' => 'master/titik_patroli',
                ];
                
                return view('layout/wrapper', $data);
                
        }
        
        $data = [   
                'title' => 'Access Forbidden',
                'content'   => 'global/notification/forbidden'
        ];

        return view('layout/wrapper',$data);
        
    }


}