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
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\siswaM;
use App\Models\kelasM;
use App\Models\jurusanM;


class ImportLive extends Component
{
    use WithFileUploads;
    use WithPagination;

    #[Validate([
        "file" => "required|file|mimes:xlsx,xls|max:2048"
    ])]

    #[Locked]
    public $idinstansi;

    public $file, $switch;

    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        $this->file = "";
        $this->switch = false;
    }

    public function render()
    {
        $siswa = siswaM::where("idinstansi", $this->idinstansi)->count();
        $kelas = kelasM::where("idinstansi", $this->idinstansi)->count();
        $jurusan = jurusanM::where("idinstansi", $this->idinstansi)->count();
        return view('livewire.import-live', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'jurusan' => $jurusan,
        ]);
    }

    public function removeimportfile()
    {
        $this->file->delete();
        $this->file = null;
    }
    

    public function importfile()
    {
        Excel::import(new SiswaImport($this->switch), $this->file);
        $this->reset("file");
        LivewireAlert::title('Success')->text("Data berhasil diimport...")->success()->show();
    }

    protected function validasi(): bool
    {
        $error = false;
        
    }
}
