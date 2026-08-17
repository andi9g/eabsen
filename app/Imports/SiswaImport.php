<?php

namespace App\Imports;

use App\Models\siswaM;
use App\Models\jurusanM;
use App\Models\kelasM;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SiswaImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    protected $parameter;

    public function __construct($parameter)
    {
        $this->parameter = $parameter;
    }

    

    public function collection(Collection $rows)
    {

        $idInstansi = session()->get("idinstansi");
        $dataUpsert = [];

        // 1. Ambil semua kelas dan jurusan yang sudah ada di instansi ini (Eager Loading ke Memory)
        $existingKelas = kelasM::where('idinstansi', $idInstansi)->pluck('idkelas', 'namakelas')->toArray();
        $existingJurusan = jurusanM::where('idinstansi', $idInstansi)->pluck('idjurusan', 'namajurusan')->toArray();

        foreach ($rows as $row) {
           $rombel = $row["rombel"];
            if (empty($rombel)) continue; // Pengaman jika kolom rombel kosong

            $ex = explode(" ", $rombel, 2);
            $namaKelas = $ex[0] ?? '';
            $namaJurusan = $ex[1] ?? '';
            
            // 2. Cek/Buat Kelas dari Memory (Bukan dari query database berulang)
            if (!isset($existingKelas[$namaKelas])) {
                $newKelas = kelasM::create([
                    "namakelas" => $namaKelas,
                    "idinstansi" => $idInstansi
                ]);
                // Masukkan ke cache memory agar baris berikutnya tidak buat kueri lagi
                $existingKelas[$namaKelas] = $newKelas->idkelas;
            }
            $idKelas = $existingKelas[$namaKelas];

            // 3. Cek/Buat Jurusan dari Memory
            if (!isset($existingJurusan[$namaJurusan])) {
                $newJurusan = jurusanM::create([
                    "namajurusan" => $namaJurusan,
                    "inisialjurusan" => $namaJurusan,
                    "idinstansi" => $idInstansi
                ]);
                // Masukkan ke cache memory
                $existingJurusan[$namaJurusan] = $newJurusan->idjurusan;
            }
            $idJurusan = $existingJurusan[$namaJurusan];
            // dd($row["tanggallahir"]);
            // 4. Tampung data siswa untuk dikirim ke upsert massal
            // dd($idInstansi);
            $dataUpsert[] = [
                "idinstansi" => $idInstansi,
                "nisn"       => $row["nisn"],
                "nis"        => $row["nis"] ?? '',
                "namasiswa"  => $row["nama"] ?? '',
                "jk"         => $row["jk"] ?? '',
                "alamat"     => $row["alamat"] ?? '',
                "hp"         => $row["hp"] ?? '',
                "tempatlahir" => $row["tempatlahir"] ?? '',
                "tanggallahir" => $row["tanggallahir"] ?? '',
                "agama" => $row["agama"] ?? '',
                "idkelas"    => $idKelas,
                "idjurusan"  => $idJurusan,
                "created_at" => now(), // Sangat disarankan jika pakai upsert manual
                "updated_at" => now(),
            ];
        }

        

        
            if($this->parameter) { //true update dan tambah
                $siswa = siswaM::upsert(
                    $dataUpsert,
                    ['idinstansi', 'nisn'],
                    ['nis', 'idinstansi', 'namasiswa', 'jk', 'alamat', 'hp', 'tempatlahir', 'tanggallahir', 'agama', 'idkelas', 'idjurusan', 'created_at', 'updated_at']
                );
            }else { //false cukup nambah data
                $siswa = siswaM::upsert(
                    $dataUpsert,
                    ['idinstansi', 'nisn'],
                    ['idinstansi']
                );
            }
       
    
    }

    public function chunkSize(): int
    {
        return 1000; 
    }
}
