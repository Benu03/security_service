<?php

namespace App\Imports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\withHeadingRow;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Support\Facades\Session;


class VehicleTempImport implements ToModel, WithStartRow 
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
    
            $tglLastService = date('Y-m-d', strtotime('1899-12-30 +' . $row[6] . ' days'));
    
            DB::connection('mtr')->table('tmp.tmp_vehicle')->insert([
                'client' => strtoupper(str_replace(' ', '', $row[0])),
                'nopol' => strtoupper(str_replace(' ', '', $row[1])),
                'norangka' => strtoupper(str_replace(' ', '', $row[2])),
                'nomesin' => strtoupper(str_replace(' ', '', $row[3])),
                'type' => $row[4],
                'tahun_pembuatan' => strtoupper(str_replace(' ', '', $row[5])),
                'tgl_last_service' => $tglLastService,
                'last_km' => $row[7],
                'nama_stnk' => $row[8],
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
