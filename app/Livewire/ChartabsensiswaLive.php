<?php

namespace App\Livewire;

use Livewire\Component;
use App\Attributes\Locked;
use Auth;
use Flux;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Validate;
use Livewire\WithPagination;
use App\Models\instansiM;
use App\Models\siswaM;
use App\Models\absensiswaM;
use App\Models\kelasM;


class ChartabsensiswaLive extends Component
{
    #[Locked]
    public $idinstansi;

    public array $data = [];
    public $tanggal, $target;
    public $dataArray= [], $values;


    public function mount()
    {
        $this->idinstansi = session('idinstansi');
        $this->tanggal = date("Y-m-d");
        $kelas = kelasM::where([
            "idinstansi" => $this->idinstansi,
        ])->get();

        foreach ($kelas as $item) {
            $this->dataArray[$item->idkelas] = siswaM::where([
                "idinstansi" => $this->idinstansi,
                "idkelas" => $item->idkelas,
            ])
            ->whereHas("kelas")
            ->whereHas("jurusan")
            ->count();
        }

        // dd($this->dataArray);
        
        foreach ($this->dataArray as $idkelas => $value) {

            $ket = [
                "h" => "Hadir",
                "i" => "Izin",
                "s" => "Sakit",
                "a" => "Alpha",
            ];
            $a = 0;
            $this->values[$idkelas][] = 0;
            foreach ($ket as $key => $status) {
                $absen = absensiswaM::where("idinstansi", $this->idinstansi)
                ->whereDate("tanggal", $this->tanggal)
                ->whereHas("siswa", function ($q) use ($idkelas) {
                    $q->where("idkelas", $idkelas);
                })->where("status", $key)
                ->distinct('idsiswa');

                $jumlah = $absen->count();
                if($key != "a") {
                    $a = $a + $jumlah;
                }else {
                    $jumlah = $this->dataArray[$idkelas] - $a;
                }

                $this->data[$idkelas][] = [
                    "Status" => $status,
                    "Jumlah" => $jumlah,
                ];
                
                $this->values[$idkelas][] = $jumlah;
                
            }
            $this->values[$idkelas][] = $value;
            
        }


    }
    public function render()
    {
        return view('livewire.chartabsensiswa-live');
    }

    public function generateTickValues($max)
    {       
        $min = 0;
        $count = 5;
        // Jika data kosong atau 0, berikan fallback agar tidak error
        if ($max <= 0) $max = 500; 

        // Hitung jarak (step) antar angka agar terbagi rata menjadi sejumlah $count elemen
        $step = $max / ($count - 1);

        // Generate array dari min sampai max dengan kelipatan step yang sudah dihitung
        return array_map('intval', range($min, $max, $step));
    }
}
