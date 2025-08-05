<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DirectService
{
    public static function getCounts()
    {
        return DB::connection('mtr')->table('mvm.mvm_direct_service')
            ->selectRaw("SUM(CASE WHEN status = 'REQUEST' THEN 1 ELSE 0 END) as countreq")
            ->selectRaw("SUM(CASE WHEN status = 'ESTIMATE' THEN 1 ELSE 0 END) as countestimate")
            ->first();
    }

    public static function getDirectData()
    {
        return DB::connection('mtr')->table('mvm.v_service_direct')
            ->whereNotIn('status', ['PROSES'])
            ->get();
    }

    public static function getBengkelData()
    {
        return Cache::remember('bengkel_list', 3600, function () {
            return DB::connection('mtr')->table('mst.v_bengkel')->get();
        });
    }
}

