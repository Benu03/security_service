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

    public function karyawan(Request $request)
    {
        
        $role = Session::get('modules')['role'] ?? null;
        if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {
                 $data = [
                        'title' => 'Master Karyawan',
                        'content' => 'master/karyawan',
                ];
                
                return view('layout/wrapper', $data);
                
        }
        
        $data = [   
                'title' => 'Access Forbidden',
                'content'   => 'global/notification/forbidden'
        ];

        return view('layout/wrapper',$data);
        
    }

    public function perusahaan(Request $request)
    {
        
        $role = Session::get('modules')['role'] ?? null;
        if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {
                 $data = [
                        'title' => 'Master Perusahan',
                        'content' => 'master/perusahaan',
                ];
                
                return view('layout/wrapper', $data);
                
        }
        
        $data = [   
                'title' => 'Access Forbidden',
                'content'   => 'global/notification/forbidden'
        ];

        return view('layout/wrapper',$data);
        
    }

    public function presensi(Request $request)
    {
        
        $role = Session::get('modules')['role'] ?? null;
        if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {
                 $data = [
                        'title' => 'Master Presensi',
                        'content' => 'master/presensi',
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