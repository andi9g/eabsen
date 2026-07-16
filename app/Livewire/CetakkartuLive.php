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
use App\Models\kelasM;
use App\Models\jurusanM;


class CetakkartuLive extends Component
{
    use WithPagination;
    #[Locked]
    public $idinstansi, $akses;

    public $instansi, $data, $paginate;
    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $this->akses = auth()->user()->akses->akses;
        $this->instansi = instansiM::when($this->idinstansi, function ($q) {
            $q->where("idinstansi", $this->idinstansi);
        })->get();
        $this->data = [];
        $this->paginate = "";
    }

    public function render()
    {   
        
        $kelas = kelasM::where("idinstansi", $this->idinstansi)->get();
        $jurusan = jurusanM::where("idinstansi", $this->idinstansi)->get();
        
        return view('livewire.cetakkartu-live',[
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    public function filtercetak()
    {
        $this->validate([
            'data.idkelas' => 'required',
            'data.idjurusan' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        $this->data["idinstansi"] = $this->idinstansi;
        $siswa = siswaM::
        where('idkelas', $this->data['idkelas'])
        ->where('idjurusan', $this->data['idjurusan'])
        ->where('idinstansi', $this->idinstansi)
        ->count();
        $page = 20;

        $this->paginate = ceil($siswa / $page);
        
    }


    protected function validasi($idinstansi):bool
    {
        $error = false;

        return $error;
    }
}
