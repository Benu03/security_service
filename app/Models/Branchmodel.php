<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branchmodel extends Model
{


    public function GetBranchTemp($username)
    {
    	$query = DB::connection('mtr')->table('tmp.tmp_branch')
                ->where('user_upload', $username)->get();
        return $query;
    }



}
