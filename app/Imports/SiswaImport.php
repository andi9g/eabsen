<?php

namespace App\Imports;

use App\Models\siswaM;
use App\Models\jurusanM;
use App\Models\kelasM;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\QueryException;

class SiswaImport implements ToModel, WithHeadingRow
{
    protected $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    public function model(array $row)
    {

        
        $rombel = $row["rombel"];
        $ex = explode(" ", $rombel, 2);
        
        $kelas = kelasM::firstOrCreate([
            "namakelas" => $ex[0],
            "idinstansi" => session()->get("idinstansi")
        ]);
        $jurusan = jurusanM::firstOrCreate([
            "namajurusan" => $ex[1],
            "inisialjurusan" => $ex[1],
            "idinstansi" => session()->get("idinstansi")
        ]);

        try {
            if($this->parameter) { //true update dan tambah
                $siswa = siswaM::updateOrCreate([
                    "idinstansi" => session()->get("idinstansi"),
                    "nisn" => $row["nisn"]
                ],[
                    "nis" => $row["nis"]??'',
                    "namasiswa" => $row["nama"]??'',
                    "jk" => $row["jk"]??'',
                    "alamat" => $row["alamat"]??'',
                    "hp" => $row["hp"]??'',
                    "idkelas" => $kelas->idkelas??'',
                    "idjurusan" => $jurusan->idjurusan??'',
                ]);
            }else { //false cukup nambah data
                $siswa = siswaM::firstOrCreate([
                    "idinstansi" => session()->get("idinstansi"),
                    "nisn" => $row["nisn"]
                ],[
                    "nis" => $row["nis"]??'',
                    "namasiswa" => $row["nama"]??'',
                    "jk" => $row["jk"]??'',
                    "alamat" => $row["alamat"]??'',
                    "hp" => $row["hp"]??'',
                    "idkelas" => $kelas->idkelas??'',
                    "idjurusan" => $jurusan->idjurusan??'',
                ]);
            }
        } catch (QueryException $e) {

            return back()->with('error', $e->getMessage());

        } catch (\Throwable $e) {

            return back()->with('error', $e->getMessage());

        }
        

        return;
    }
}
