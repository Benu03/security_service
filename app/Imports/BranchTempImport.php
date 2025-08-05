<?php

namespace App\Imports;

use App\Models\Branch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\withHeadingRow;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Support\Facades\Session;


class BranchTempImport implements ToModel, WithStartRow 
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
  
 


    public function startRow(): int
    {
        return 2;
    }

    public function model(array $row)
    {
   
        try {
            if (!array_filter($row)) {
                return null;
            }
    
            // $tglLastService = date('Y-m-d', strtotime('1899-12-30 +' . $row[6] . ' days'));
    
            DB::connection('mtr')->table('tmp.tmp_branch')->insert([
                'client' => strtoupper(trim($row[0])),
                'regional' => strtoupper(trim($row[1])),
                'area' => trim($row[2]),
                'branch' => trim($row[3]),
                'pic_branch' => trim($row[4]),
                'phone' => trim($row[5]),
                'address' => trim($row[6]),
                'upload_date' => date("Y-m-d h:i:sa"), 
                'user_upload' => Session::get('user_module')['username'],
            ]);
    
            return null; // Mengembalikan null karena data sudah dimasukkan menggunakan Query Builder
        } catch (\Exception $e) {
            // Menampilkan pesan error
            Log::error('Error importing data: ' . $e->getMessage());
            return null;
        }

    }
}
