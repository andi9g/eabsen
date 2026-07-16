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
use App\Models\absensiswaM;
use App\Models\walikelasM;
use App\Models\kelasM;
use App\Models\jurusanM;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LaporanabsensiswaLive extends Component
{
    #[Locked]
    public $idinstansi;

    public $kelas, $jurusan;
    public $data = [];

    public function mount()
    {
        $akses = auth()->user()->akses->akses??"user";
        $this->idinstansi = session("idinstansi");
        if($akses == "pegawai") {
            $walikelas = walikelasM::where([
                "iduser" => auth()->user()->iduser,
                "idinstansi" => $this->idinstansi,
                "idsemester" => session("semester"),
            ])->with("kelas", "jurusan")
            ->get();

            $this->kelas = $walikelas->pluck("kelas.namakelas", "idkelas");
            $this->jurusan = $walikelas->pluck("jurusan.inisialjurusan", "idjurusan");
           
        }else {
             $this->kelas = kelasM::where([
                "idinstansi" => $this->idinstansi,
            ])->pluck("namakelas", "idkelas");
            $this->jurusan = jurusanM::where([
                "idinstansi" => $this->idinstansi,
            ])->pluck("inisialjurusan", "idjurusan");
        }
        Carbon::setLocale("id");
    }

    public function render()
    {
        return view('livewire.laporanabsensiswa-live');
    }

    public function cetaklaporan()
    {
        $this->validate([
            'data.kelas' => 'required',
            'data.jurusan' => 'required',
            'data.tanggal' => 'required|array',
        ],[
            "required" => "Field wajib di isi.",
            "array" => "Tanggal tidak falid",
        ]);

        // dd($this->data["tanggal"]["start"]);
        $tanggal = CarbonPeriod::create($this->data["tanggal"]["start"], $this->data["tanggal"]["end"]);
        $hariBlacklist = ['Sabtu', 'Minggu'];

        $tanggal = $tanggal->addFilter(function ($date) use ($hariBlacklist) {
            Carbon::setLocale("id");
            $namaHari = Carbon::parse($date)->isoFormat('dddd'); 
            return !in_array($namaHari, $hariBlacklist);
        });
        $tanggal = $tanggal->map(fn($date) => $date->format('Y-m-d'));


    }
}
