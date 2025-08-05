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


class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $role = Session::get('modules')['role'] ?? null;

         $menus = DB::table('mst.mst_menu')
                    ->leftJoin('mst.mst_role_menu', 'mst.mst_menu.id', '=', 'mst.mst_role_menu.mst_menu_id')
                    ->leftJoin('mst.mst_user_role', 'mst.mst_role_menu.mst_user_role_id', '=', 'mst.mst_user_role.id')
                    ->where('mst_user_role.role_name', $role ?? '')
                    ->where('mst_menu.is_active', true)
                    ->orderBy('mst.mst_menu.menu_order', 'asc')
                    ->select('mst.mst_menu.*', 'mst.mst_role_menu.mst_user_role_id', 'mst.mst_user_role.role_name')
                    ->get();

        $menuTree = $menus->groupBy('menu_parent');

        Session::put('menus', $menuTree);
            

        if ($role === 'ADMIN') {
            return $this->dashAdmin();
        } 
        elseif ($role === 'SUPER ADMIN') {
            return $this->dashSuperAdmin();
        } 
        else 
        {
            $data = [
                'title' => 'Access Forbidden',
                'content' => 'global/notification/forbidden',
            ];

            return view('layout/wrapper', $data);
        }
    }

    private function dashAdmin()
    {

       

        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard/admin',
        ];
    
        return view('layout/wrapper', $data);
    }

    private function dashSuperAdmin()
    {

        $data = [
            'title' => 'Dashboard',
            'content' => 'dashboard/super_admin',
        ];
    
        return view('layout/wrapper', $data);
    }
    




}