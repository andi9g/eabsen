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
use App\Models\perangkatM;
use Str;

class PerangkatLive extends Component
{
    use WithPagination;
    #[Locked]
    public $iduser, $idinstansi;

    public $search, $target;
    public $value, $tab;

    public function mount()
    {
        $this->iduser = auth()->user()->iduser;
        $this->idinstansi = session()->get("idinstansi");
        $this->target = "register";
        $this->tab = "pengelola";
    }

    public function render()
    {
        $data = perangkatM::when($this->search, function($q) {
            $q->where(function($query) {
                $query->where("kodeperangkat", "like", "%$this->search%");
            });
        })->where("target", $this->target)
        ->where("idinstansi", $this->idinstansi)
        ->orderBy("idperangkat", "asc")
        ->paginate(10);
         
        return view('livewire.perangkat-live', [
            "data" => $data
        ]);
    }


    public function buttontambahalatregisterasi()
    {
        LivewireAlert::title("Tambah Alat ".ucfirst($this->target))
            ->withConfirmButton('Tambah')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('tambahalat')
            ->show();
        
        
    }
    public function tambahalat()
    {
        $kode = Str::random(10);
        
        if($this->target == "register") {
            perangkatM::create([
                "idinstansi" => $this->idinstansi,
                "kodeperangkat" => $kode,
                "fungsiperangkat" => "register",
                "target" => "register",
                "action" => "none",
                "uuid" => "",
            ]);

        }else if($this->target == "absen") {
            perangkatM::create([
                "idinstansi" => $this->idinstansi,
                "kodeperangkat" => $kode,
                "fungsiperangkat" => "absen",
                "target" => "absen",
                "action" => "timer",
                "uuid" => "",
            ]);
        }

        LivewireAlert::title('Success')->text("Perangkat berhasil dibuat...")->success()->show();
    }

    public function buttonhapus($idperangkat)
    {
        if($this->validasi($idperangkat)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        
        LivewireAlert::title('Hapus data?')
            ->text("Pastikan tidak ada perangkat yang menggunakan kode alat ini, karena akan mengalami kerusakan")
            ->withConfirmButton('Delete')
            ->warning()
            ->timer(null)
            ->withCancelButton('Cancel')
            ->onConfirm('deleteItem', ['id' => $idperangkat])
            ->show();
        
        
    }

    public function pindah()
    {
        if($this->tab == "pengelola") {
            $this->target = "register";
        }else if($this->tab == "absensiswa") {
            $this->target = "absen";
        }
    }

    public function deleteItem($data)
    {
        $idperangkat = $data['id'];
        perangkatM::destroy($idperangkat);
        LivewireAlert::title('Success')->text("Perangkat berhasil dihapus!")->success()->show();
    }

    public function buttondetail($idperangkat)
    {
        if($this->validasi($idperangkat)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        $perangkat = perangkatM::findOrFail($idperangkat);

        if($perangkat->fungsiperangkat == "register") {
            $fungsiperangkat = "PENGELOLA";
        }else if($perangkat->fungsiperangkat == "absen"){
            $fungsiperangkat = "SISWA";
        }else {
            $fungsiperangkat = "CUSTOM";
        }

        $this->value = [
            "kodeperangkat" => $perangkat->kodeperangkat,
            "fungsiperangkat" => $fungsiperangkat,
            "target" => $perangkat->target,
            "action" => strtoupper($perangkat->action),
            "api" => "https://apiabsen.wardarizka.web.id",
        ];

        Flux::modal('detailperangkat')->show();
    }

    protected function validasi($idperangkat):bool 
    {
        $error = false;
        $perangkat = perangkatM::findOrFail($idperangkat);

        if($perangkat->idinstansi != $this->idinstansi) {
            $error = true;
        }

        return $error;
    }
}
