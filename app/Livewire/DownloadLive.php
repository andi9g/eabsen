<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\instansiM;

class DownloadLive extends Component
{
    #[Locked]
    public $instansi;

    public function mount()
    {
        $this->instansi = instansiM::find(session()->get('idinstansi'));
    }
    public function render()
    {
        return view('livewire.download-live');
    }
}
