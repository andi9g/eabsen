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
use App\Models\siswaM;
use App\Models\kelasM;
use App\Models\jurusanM;
use App\Models\perangkatM;
use App\Models\kartuM;


class RegisterasiLive extends Component
{
    use WithPagination;

    #[Locked]
    public $iduser, $idinstansi;
    
    public $value, $search, $idkelas, $idjurusan, $idperangkat, $uuid;
    public $jurusan, $kelas, $perangkat;
    public $status;

    public function mount()
    {
        $this->iduser = auth()->user()->iduser;
        $this->idinstansi = session()->get("idinstansi");
        $this->value = "";
        $this->search = "";
        $this->idkelas = "";
        $this->idjurusan = "";
        $this->idperangkat = "";
        $this->status = "";
        $this->uuid = "";
        $this->kelas = kelasM::where("idinstansi", $this->idinstansi)->get();
        $this->jurusan = jurusanM::where("idinstansi", $this->idinstansi)->get();
        $this->perangkat = perangkatM::where("idinstansi", $this->idinstansi)
        ->where("target", "register")
        ->orderBy("idperangkat", "asc")
        ->get();
    }

    
    public function render()
    {
        $siswa = siswaM::when($this->search, function($query) {
            $query->where(function($q){
                $q->where("namasiswa", "like", "%".$this->search."%")
                ->orWhere("nisn", "like", "%".$this->search."%");
            });
        })
        ->when($this->idkelas, function($query) {
            $query->where("idkelas", $this->idkelas);
        })
        ->when($this->idjurusan, function($query) {
            $query->where("idjurusan", $this->idjurusan);
        })
        ->where("idinstansi", $this->idinstansi)
        ->paginate(10);


        return view('livewire.registerasi-live', [
            'siswa' => $siswa,
        ]);
    }

    public function buttontambahkartu($idsiswa)
    {
        if($this->validasi($idsiswa)) {
            $this->value = [];
            LivewireAlert::title('error')->error()->show();
            return;
        }
        // $this->status = "";

        $siswa = siswaM::findOrFail($idsiswa);
        
        $this->value = [
            "idsiswa" => $siswa->idsiswa,
            "nisn" => $siswa->nisn,
            "namasiswa" => "[".$siswa->kelas->namakelas." ".$siswa->jurusan->inisialjurusan."] ".$siswa->namasiswa,
            "namaperangkat" => "Kode Alat : ".($this->perangkat->where("idperangkat", $this->idperangkat)->first()->kodeperangkat??""),
            "uuid" => $this->uuid,
        ];

        Flux::modal('buttontambahkartu')->show();

    }

    public function getUUID()
    {
        if($this->validasi($this->value["idsiswa"]??"")) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        $perangkat = perangkatM::find($this->idperangkat);
        $this->uuid = $perangkat->uuid??"";
        $this->value["uuid"] = $this->uuid;

        $kartu = kartuM::where("idinstansi", $this->idinstansi)
        ->where("uuid", $this->value["uuid"])->count();

        // dd($kartu);
        if(empty($this->value["uuid"])) {
            $this->status = "kosong";
        }else {
            if($kartu <= 0 ) {
                $this->status = "belumterdaftar";
            }else {
                $this->status = "sudahterdaftar";
            }
        }
        $this->uuid = $this->value["uuid"];
    }



    public function pilihperangkat()
    {
        $idperangkat = $this->idperangkat;
        if(empty($idperangkat)) {
             Flux::toast(text:'Alat Registerasi dilepaskan.', variant:'success');
            return;
        }
        $perangkat = perangkatM::findOrFail($idperangkat)->update([
            "uuid" => ""
        ]);

        Flux::toast(text:'Alat Registerasi berhasil dipilih dan Dikosongkan', variant:'success', heading: 'Alat siap untuk digunakan!');
    }



    public function tambahkartu()
    {
        if($this->validasi($this->value["idsiswa"]??"")) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        if($this->status == "belumterdaftar") {
            $kartu = kartuM::create([
                "idinstansi" => $this->idinstansi,
                "idsiswa" => $this->value["idsiswa"],
                "uuid" => $this->value["uuid"],
            ]);

            if($kartu) {
                LivewireAlert::title('Success')->text('Kartu berhasil didaftarkan.')->success()->show();
                $this->value = [];
                $this->status = "";
                Flux::modals()->close();
            }else {
                LivewireAlert::title('error')->error()->show();
            }
        }
    }

    public function hapuskartu($idsiswa)
    {
        if($this->validasi($idsiswa)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        LivewireAlert::title('Hapus kartu siswa?')
            ->text("Kartu yang sudah dihapus tidak dapat dikembalikan. Pastikan anda memilih kartu yang benar.")
            ->withConfirmButton('Delete')
            ->warning()
            ->withCancelButton('Cancel')
            ->onConfirm('hapuskartusiswa', ['id' => $idsiswa])
            ->show();
    }

    public function hapuskartusiswa($data)
    {
        $idsiswa = $data['id'];
        $siswa = siswaM::findOrFail($idsiswa);
        if($siswa->kartu()->exists()) {
            $siswa->kartu()->delete();
            LivewireAlert::title('Success')->text('Kartu berhasil dihapus.')->success()->show();
        }
    }



    protected function validasi($idsiswa):bool
    {
        $error = false;
        $siswa = siswaM::findOrFail($idsiswa);
        if($siswa->idinstansi != $this->idinstansi) {
            $this->value = [];
            $error = true;
        }
        if(!empty($this->idperangkat)) {
            $perangkat = perangkatM::find($this->idperangkat);
            if($perangkat->idinstansi != $this->idinstansi) {
                $this->value = [];
                $error = true;
            }
        }

        return $error;
    }
}
