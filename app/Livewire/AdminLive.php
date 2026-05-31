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
use App\Models\instansiM;

class AdminLive extends Component
{
   use WithPagination;
    public $search;
    #[Locked]
    public $idinstansi;
    public $name, $email, $instansi, $npsn;
    
    public function mount()
    {
        $this->search = "";
        $this->name = "";
        $this->email = "";
        $this->instansi = "";
        $this->npsn = "";
        $this->idinstansi = auth()->user()->detailuser->idinstansi??null;
    }

    public function render()
    {
        $user = User::when($this->search, function($q) {
            $q->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })->whereHas('akses', function($query) {
            $query->whereIn('akses', ["superadmin", "admin"]);
        })->when($this->idinstansi, function($q) {
            $q->whereHas('detailuser', function($query) {
                $query->where('idinstansi', $this->idinstansi);
            });
        })
        ->paginate(15);
        return view('livewire.admin-live', [
            "user" => $user
        ]);
    }

    public function tomboltambahadmin()
    {
        Flux::modal('tomboltambahadmin')->show();
    }

    public function tambahadmin()
    {
        $npsn = "";
        if($this->idinstansi) {
            $this->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
            ],[
                "required" => "Field wajib di isi.",
                "email.unique" => "Email sudah digunakan.",
            ]);

            $instansi = instansiM::find($this->idinstansi);
            $user = User::create([
                "name" => $this->name,
                "email" => $this->email,
                "email_verified_at" => now(),
                "is_default_password" => true,
                "password" => bcrypt($instansi->npsn),
            ]);

            if($user) {
                $user->akses()->create([
                    "akses" => "admin"
                ]);
                $user->detailuser()->create([
                    "idinstansi" => $this->idinstansi
                ]);
            }

            $npsn = $instansi->npsn;
        } else {
            $this->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'instansi' => 'required',
                'npsn' => 'required|numeric|unique:instansi,npsn',
            ],[
                "required" => "Field wajib di isi.",
                "email.unique" => "Email sudah digunakan.",
                "npsn.unique" => "NPSN sudah digunakan.",
            ]);

            $user = User::create([
                "name" => $this->name,
                "email" => $this->email,
                "email_verified_at" => now(),
                "is_default_password" => true,
                "password" => bcrypt($this->npsn),
            ]);

            $instansi = instansiM::create([
                "namainstansi" => $this->instansi,
                "npsn" => $this->npsn,
            ]);
            if($user) {
                $user->akses()->create([
                    "akses" => "admin"
                ]);
                $user->detailuser()->create([
                    "idinstansi" => $instansi->idinstansi
                ]);
            }

            $npsn = $this->npsn;
        }

        
        Flux::modals()->close();
        LivewireAlert::title('Success')->title("Password NPSN: " . $npsn)->success()->show();
    }
}
