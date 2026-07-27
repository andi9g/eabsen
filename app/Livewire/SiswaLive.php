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

 use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;


class SiswaLive extends Component
{
    use WithFileUploads;
    use WithPagination;

    // #[Validate('image|max:2000')] 
   

    #[Locked]
    public $idinstansi;

    public $data, $kelas, $jurusan;
    public $search, $update;

    #[Validate('image|max:2000', message: 'File harus berupa gambar maksimal 2MB')]
    public $gambar; 

    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $this->update = false;
        $this->kelas = kelasM::where("idinstansi", $this->idinstansi)->get();
        $this->jurusan = jurusanM::where("idinstansi", $this->idinstansi)->get();
        $this->gambar='';
    }

    public function render()
    {
        $siswa = siswaM::when($this->search, function($query) {
            $query->where(function($query) {
                $query->where("namasiswa", "like", "%$this->search%")
                ->orWhere("nisn", "like", "$this->search%")
                ->orWhere("nis", "like", "$this->search%");
            });
        })
        ->whereHas("kelas")
        ->whereHas("jurusan")
        ->when($this->idinstansi, function($query) {
            $query->where("idinstansi",  $this->idinstansi);
        })->orderBy("namasiswa", "asc")
        ->paginate(15);
        // dd($siswa);
        return view('livewire.siswa-live', [
            'siswa' => $siswa,
        ]);
    }

    public function bukagambar(array $siswa)
    {
        $idsiswa = array_key_first($siswa);
        if ($this->validasi($idsiswa)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        
        $this->data['idsiswa'] = $idsiswa;
        $this->data['foto'] = reset($siswa);
        Flux::modal('modal-gambar')->show();
        
    }

    public function updategambar()
    {
        $path = $this->imageCompresh($this->gambar);
        $this->data["foto"] = $path;
        siswaM::where("idsiswa", $this->data["idsiswa"])->update([
            "foto" => $this->data["foto"],
        ]);

        $this->reset('gambar');
        Flux::modals()->close();
    }

   
    
    public function imageCompresh($file): string
    {
        $quality = 75;
        $folder = 'profil/siswa';
    
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $path = "$folder/$filename";
    
        $image = Image::decode($file)
        ->scale(height:300);
    
        Storage::disk("s3")->put(
            $path,
            $image->encodeUsingFileExtension($file->getClientOriginalExtension(), quality: $quality)
        );
    
        return $path;
    }

    public function buttonhapus($idsiswa)
    {
        if($this->validasi($idsiswa)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        LivewireAlert::title('Hapus data?')
            ->withConfirmButton('Delete')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('hapussiswa', ['idsiswa' => $idsiswa])
            ->show();
    }

    public function removenamafungsi()
    {
        $this->gambar ='';
    }

    public function buttontambahsiswa()
    {
        $this->update = false;
        $this->data = [];
        Flux::modal('buttontambahsiswa')->show();
    }
    public function buttonupdatesiswa($idsiswa)
    {
        
        if($this->validasi($idsiswa)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        $siswa = siswaM::findOrFail($idsiswa);
        $this->update = true;
        $this->data = [
            "idsiswa" => $siswa->idsiswa,
            "idkelas" => $siswa->idkelas,
            "idjurusan" => $siswa->idjurusan,
            "nisn" => $siswa->nisn,
            "nis" => $siswa->nis,
            "namasiswa" => $siswa->namasiswa,
            "alamat" => $siswa->alamat,
            "jk" => $siswa->jk,
            "hp" => $siswa->hp
        ];
        $this->resetValidation();
        Flux::modal('buttontambahsiswa')->show();
    }


    public function tambahdata()
    {
        
        $this->validate([
            'data.namasiswa' => 'required',
            'data.nisn' => 'required',
            'data.nis' => 'required',
            'data.alamat' => 'required',
            'data.idkelas' => 'required',
            'data.idjurusan' => 'required',
            'data.jk' => 'required',
            'data.hp' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        $text = "";
        if($this->update == false){
            $siswa = siswaM::create([
                "idinstansi" => $this->idinstansi,
                "nisn" => $this->data["nisn"],
                "namasiswa" => $this->data["namasiswa"],
                "nis" => $this->data["nis"],
                "idkelas" => $this->data["idkelas"],
                "idjurusan" => $this->data["idjurusan"],
                "jk" => $this->data["jk"],
                "alamat" => $this->data["alamat"],
                "hp" => $this->data["hp"],
            ]);
            $text = "Data berhasil ditambahkan.";
        }else {
            $siswa = siswaM::findOrFail($this->data["idsiswa"])->update([
                "namasiswa" => $this->data["namasiswa"],
                "nis" => $this->data["nis"],
                "idkelas" => $this->data["idkelas"],
                "idjurusan" => $this->data["idjurusan"],
                "jk" => $this->data["jk"],
                "alamat" => $this->data["alamat"],
                "hp" => $this->data["hp"],
            ]);
            $text = "Data berhasil diperbaruhi.";
        }

        Flux::modals()->close();
        LivewireAlert::title('Success')->text($text)->success()->show();



    }

    public function hapussiswa($data)
    {
        $idsiswa = $data['idsiswa'];
        siswaM::destroy($idsiswa);
        LivewireAlert::title('Success')->text("Data berhasil dihapus!")->success()->show();
    }

    protected function validasi($idsiswa):bool
    {
        $error = false;

        if(!(auth()->user()->detailuser->idinstansi == $this->idinstansi)){
            $error = true;
        }

        $siswa = siswaM::findOrFail($idsiswa);
        if($siswa->idinstansi != $this->idinstansi) {
            $error = true;
        }
        return $error;
    }
}
