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


class LaporansiswaLive extends Component
{
    #[Locked]
    public $idinstansi, $idsemester;


    public function mount()
    {
        $this->idinstansi = session('idinstansi');
        $this->idsemester = session('idsemester');
        
    }
    public function render()
    {
        return view('livewire.laporansiswa-live');
    }

    public function getKelasProperty()
    {
        return kelasM::where("idinstansi", $this->idinstansi)->orderBy('namakelas', 'asc')->get();
    }
    public function getJurusanProperty()
    {
        return jurusanM::where("idinstansi", $this->idinstansi)->orderBy('inisialjurusan', 'asc')->get();
    }
}
