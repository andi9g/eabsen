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
use App\Models\jurusanM;
use App\Models\kelasM;
use App\Models\siswaM;

class RombelLive extends Component
{
    use WithPagination;

    #[Locked]
    public $idinstansi;

    public $searchkelas, $searchjurusan;
    public $data, $updatekelas, $updatejurusan;

    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $this->data = [];  
        $this->updatekelas = false;  
        $this->updatejurusan = false;  
        $this->searchkelas = "";
        $this->searchjurusan = "";
    }

    public function render()
    {
        $kelas = kelasM::when($this->searchkelas, function($query){
            $query->where("namakelas", "like", "%$this->searchkelas%");
        })->orderBy("namakelas", "asc")
        ->when($this->idinstansi, function($query) {
            $query->where("idinstansi", $this->idinstansi);
        })
        ->paginate(10);
        
        $jurusan = jurusanM::when($this->searchjurusan, function($query){
            $query->where(function($query) {
                $query->where("namajurusan", "like", "%$this->searchjurusan%")
                ->orWhere("inisialjurusan", "like", "%$this->searchjurusan%");
            });
            
        })->when($this->idinstansi, function($query) {
            $query->where("idinstansi", $this->idinstansi);
        })
        ->orderBy("namajurusan", "asc")->paginate(10);


        return view('livewire.rombel-live', [
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    public function buttontambahkelas()
    {
        $this->data = [];
        $this->updatekelas = false;
        Flux::modal('buttontambahkelas')->show();
    }
    public function buttontambahjurusan()
    {
        $this->data = [];
        $this->updatejurusan = false;
        Flux::modal('buttontambahjurusan')->show();
    }

    public function buttonupdatekelas($idkelas)
    {
        if($this->validasi("kelas", $idkelas)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        $this->updatekelas = true;
        $kelas = kelasM::findOrFail($idkelas);
        $this->data = [
            "idkelas" => $kelas->idkelas,
            "namakelas" => $kelas->namakelas,
        ];

        Flux::modal('buttontambahkelas')->show();
    }

    public function buttonupdatejurusan($idjurusan)
    {
        if($this->validasi("jurusan", $idjurusan)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        $this->updatejurusan = true;
        $jurusan = jurusanM::findOrFail($idjurusan);
        $this->data = [
            "idjurusan" => $jurusan->idjurusan,
            "namajurusan" => $jurusan->namajurusan,
            "inisialjurusan" => $jurusan->inisialjurusan,
        ];

        Flux::modal('buttontambahjurusan')->show();
    }

    public function formkelas()
    {
        $this->validate([
            'data.namakelas' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        if($this->updatekelas) {
            kelasM::findOrFail($this->data["idkelas"])->update([
                "namakelas" => $this->data["namakelas"]
            ]);
            $text = "Data berhasil diupdate.";
        }else {
            kelasM::create([
                "idinstansi" => $this->idinstansi,
                "namakelas" => $this->data["namakelas"],
            ]);
            $text = "Data berhasil ditambahkan.";
        }

        Flux::modals()->close();
        LivewireAlert::title('Success')->success()->text($text)->show();
    }
    public function formjurusan()
    {
        $this->validate([
            'data.namajurusan' => 'required',
            'data.inisialjurusan' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        if($this->updatejurusan) {
            jurusanM::findOrFail($this->data["idjurusan"])->update([
                "namajurusan" => $this->data["namajurusan"],
                "inisialjurusan" => $this->data["inisialjurusan"],
            ]);
            $text = "Data berhasil diupdate.";
        }else {
            jurusanM::create([
                "idinstansi" => $this->idinstansi,
                "namajurusan" => $this->data["namajurusan"],
                "inisialjurusan" => $this->data["inisialjurusan"],
            ]);
            $text = "Data berhasil ditambahkan.";
        }
        Flux::modals()->close();
        LivewireAlert::title('Success')->success()->text($text)->show();
    }


    public function hapuskelas($idkelas)
    {
        if($this->validasi("kelas", $idkelas)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        LivewireAlert::title('Hapus data?')
            ->withConfirmButton('Delete')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('deletekelas', ['idkelas' => $idkelas])
            ->show();
        
    }

    public function hapusjurusan($idjurusan)
    {
        if($this->validasi("jurusan", $idjurusan)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        LivewireAlert::title('Hapus data?')
            ->withConfirmButton('Delete')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('deletejurusan', ['idjurusan' => $idjurusan])
            ->show();
        
    }

    public function deletekelas($data)
    {
        $idkelas = $data['idkelas'];
        kelasM::destroy($idkelas);
        LivewireAlert::title('Success')->success()->show();
    }

   

    public function deletejurusan($data)
    {
        $idjurusan = $data['idjurusan'];
        jurusanM::destroy($idjurusan);
        LivewireAlert::title('Success')->success()->show();
    }


    protected function validasi($target, $id):bool
    {
        $error = false;

        if(!(auth()->user()->detailuser->idinstansi == $this->idinstansi)){
            $error = true;
        }

        if($target == "kelas") {
            $kelas = kelasM::findOrFail($id);
            if($kelas->idinstansi != $this->idinstansi) {
                $error = true;
            }
        }elseif($target == "jurusan") {
            $jurusan = jurusanM::findOrFail($id);
            if($jurusan->idinstansi != $this->idinstansi) {
                $error = true;
            }
        }
        return $error;
    }



}
