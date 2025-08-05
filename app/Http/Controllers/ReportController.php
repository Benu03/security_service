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


class ReportController extends Controller
{


  public function report(Request $request)
    {
        
        $role = Session::get('modules')['role'] ?? null;
        if ($role === 'ADMIN' || $role === 'SUPER ADMIN') {
                 $data = [
                        'title' => 'Report Page',
                        'content' => 'report/report',
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