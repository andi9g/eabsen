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
    public $idinstansi, $iduser;

    public $name, $email, $instansi, $npsn;
    
    public function mount()
    {
        $this->search = "";
        $this->name = "";
        $this->email = "";
        $this->instansi = "";
        $this->npsn = "";
        $this->idinstansi = auth()->user()->detailuser->idinstansi??null;
        $this->iduser = auth()->user()->iduser??null;
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
        if(!($this->idinstansi == "")) {
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

    public function hapusadmin($iduser)
    {
        
        if($this->validasi($iduser)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }

        LivewireAlert::title('Hapus data?')
            ->withConfirmButton('Delete')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('deleteItem', ['id' => $iduser])
            ->show();
        
        
    }
    public function deleteItem($data)
    {
        $iduser = $data['id'];
        if($iduser == $this->iduser) {
            LivewireAlert::title('error')->text("Anda tidak dapat menghapus akun Anda sendiri.")->error()->show();
            return;
        }

        $user = User::find($iduser);
        
        // dd($this->idinstansi);
        if($this->idinstansi && (($user->detailuser->idinstansi??null) != $this->idinstansi)) {
            LivewireAlert::title('error')->text("Anda tidak memiliki akses untuk menghapus admin dari instansi lain.")->error()->show();
            return;
        }

        $user->akses()->delete();
        $user->detailuser()->delete();
        $user->delete();
        LivewireAlert::title('Success')->text("Admin berhasil dihapus.")->success()->show();
    }

    public function resetkey($iduser)
    {
        if($this->validasi($iduser)) {
            LivewireAlert::title('error')->error()->show();
            return;
        }
        LivewireAlert::title('Reset Password?')
            ->withConfirmButton('Reset')
            ->question()
            ->withCancelButton('Cancel')
            ->onConfirm('resetPassword', ['id' => $iduser])
            ->show();
        
        
    }
    
    public function resetPassword($data)
    {
        $iduser = $data['id'];
        if($iduser == $this->iduser) {
            LivewireAlert::title('error')->text("Anda tidak dapat mereset akun Anda sendiri.")->error()->show();
            return;
        }
        $user = User::find($iduser);

         // dd($this->idinstansi);
        if($this->idinstansi && (($user->detailuser->idinstansi??null) != $this->idinstansi)) {
            LivewireAlert::title('error')->text("Anda tidak memiliki akses untuk mereset password admin dari instansi lain.")->error()->show();
            return;
        }

        if($user->akses->akses == "superadmin") {
            LivewireAlert::title('warning')->text("Akun superadmin tidak dapat direset")->warning()->show();
            return;
        }
        $password = bcrypt($user->detailuser->instansi->npsn);
        $user->update([
            "password" => $password,
            "is_default_password" => true,
        ]);

        LivewireAlert::title('Success')->text("Password NPSN: ".$user->detailuser->instansi->npsn)->success()->show();
    }

    protected function validasi($iduser): bool
    {
        $error = false;
        $user = User::with('akses')->findOrFail($iduser);
        if($user->akses->akses == "user") {
            // dd($user->akses->akses);
            $error = true;
        }
        if($user->akses->akses == "pegawai") {
            // dd($user->akses->akses);
            $error = true;
        }

        return $error;
    }
}
