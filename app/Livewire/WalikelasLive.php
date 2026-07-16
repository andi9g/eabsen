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
use App\Models\kelasM;
use App\Models\jurusanM;
use App\Models\walikelasM;
use App\Models\User;

class WalikelasLive extends Component
{
    use WithPagination;

    #[Locked]
    public $idinstansi, $idsemester;

    public $kelas, $jurusan, $pegawai;
    public $data, $update, $search;
    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $this->idsemester = session()->get("semester");
        $this->update = false;
        $this->data = [];
        $this->kelas = kelasM::where("idinstansi", $this->idinstansi)->get();
        $this->jurusan = jurusanM::where("idinstansi", $this->idinstansi)->get();
        $this->pegawai = User::whereHas("detailuser", function ($q) {
            $q->where("idinstansi", $this->idinstansi);
        })->whereHas("akses", function ($q) {
            $q->where("akses", "pegawai");
        })->get();

        
    }

    public function render()
    {
        $walikelas = walikelasM::where("idinstansi", $this->idinstansi)
        ->where("idsemester", session()->get("semester"))
        ->when($this->idsemester, function ($query) {
            $query->where("idsemester", $this->idsemester);
        })
        ->when($this->search, function ($query) {
            $query->whereHas("user", function ($q) {
                $q->where("name", "like", "%{$this->search}%");
            })->orWhereHas("kelas", function ($q) {
                $q->where("kelas", "like", "%{$this->search}%");
            })->orWhereHas("jurusan", function ($q) {
                $q->where("jurusan", "like", "%{$this->search}%");
            });
        })
        ->paginate(15);

        return view('livewire.walikelas-live', [
            'walikelas' => $walikelas,
        ]);
    }

    public function buttonformwalikelas()
    {
        $this->data = [];
        $this->update = false;
        Flux::modal('formwalikelas')->show();
    }
    public function buttonupdate($idwalikelas)
    {
        $this->data = walikelasM::where("idwalikelas", $idwalikelas)->first()->toArray();
        $this->update = true;
        Flux::modal('formwalikelas')->show();
    }
    public function buttonhapus($idwalikelas)
    {
        if($this->validasi($idwalikelas)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        LivewireAlert::title('Hapus data?')
            ->withConfirmButton('Delete')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('deleteItem', ['idwalikelas' => $idwalikelas])
            ->show();
        
        
    }
    public function deleteItem($data)
    {
        $itemId = $data['idwalikelas'];
        walikelasM::where("idwalikelas", $itemId)->delete();
        LivewireAlert::title('Success')->success()->show();
    }

    protected function validasi($idwalikelas):bool
    {
        $error = true;

        $walikelas = walikelasM::where("idwalikelas", $idwalikelas)
        ->where("idinstansi", $this->idinstansi)
        ->exists();
        if($walikelas) {
            $error = false;
        }
        
        return $error;



    }


    public function formwalikelas()
    {
        $this->validate([
            'data.iduser' => 'required',
            'data.idkelas' => 'required',
            'data.idjurusan' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        if($this->update) {
            walikelasM::where([
                "idwalikelas" => $this->data["idwalikelas"],
                "idsemester" => session()->get("semester"),
            ])->update([
                'iduser' => $this->data["iduser"],
            ]);
        } else {
            walikelasM::updateOrCreate([
                "idinstansi" => $this->idinstansi,
                "idjurusan" => $this->data["idjurusan"],
                "idkelas" => $this->data["idkelas"],
                "idsemester" => session()->get("semester"),
            ], [
                'iduser' => $this->data["iduser"],
            ]);
        }
    
        Flux::modals()->close();
        LivewireAlert::title('Success')->success()->show();
    }
}
