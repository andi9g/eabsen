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
use App\Models\User;


class DatadiriLive extends Component
{

    #[Locked]
    public $idinstansi, $iduser;

    public $data;
    public function mount()
    {
        $this->idinstansi = session()->get("idinstansi");
        
    }
    
    public function render()
    {
        $data = User::find(auth()->user()->iduser);
        $this->data = [
            "iduser" => $data->iduser,
            "name" => $data->name,
            "nip" => $data->detailuser->nip??'',
            "alamat" => $data->detailuser->alamat??'',
        ];
        return view('livewire.datadiri-live');
    }

    public function ubahdatadiri()
    {
        // dd("berhasil");
        $this->validate([
            'data.name' => 'required',
            'data.nip' => 'required|numeric',
            'data.alamat' => 'required',
        ],[
            "required" => "Field wajib di isi.",
        ]);

        $user = User::where('iduser', auth()->user()->iduser)->first();
        $user->update([
            "name" => $this->data["name"],
        ]);

        $user->detailuser->update([
            "nip" => $this->data["nip"],
            "alamat" => $this->data["alamat"],
        ]);

        LivewireAlert::title('Success')->success()->show();
        
    }
}
