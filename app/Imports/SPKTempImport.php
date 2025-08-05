<?php

namespace App\Imports;

use App\Models\Spk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\withHeadingRow;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class SPKTempImport implements ToModel, WithStartRow 
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
   
        if(!array_filter($row)) {
            return null;
         } 
         
        return new Spk([
            'user_upload' => Session::get('user_module')['username'],
            'nopol' => strtoupper(str_replace(' ', '', $row[0])),
            'nomesin' => $row[1], 
            'norangka' => $row[2], 
            'tahun_pembuatan' => $row[3], 
            'type' => $row[4], 
            'branch' => $row[5], 
            'remark' => $row[6],
            'upload_date'	=> date("Y-m-d h:i:sa")      
        ]);
    }
}
