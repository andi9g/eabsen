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

class UserLive extends Component
{
    use WithPagination;

    #[Locked]
    public $iduser, $idinstansi, $npsn;

    public $search;
    public $email, $name;

    public function mount()
    {
        $this->iduser = auth()->user()->iduser;
        $this->idinstansi = session()->get("idinstansi");
        $this->npsn = auth()->user()->detailuser->instansi->npsn;
    }
    public function render()
    {
        $user = User::when($this->search, function($q) {
            $q->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })->whereHas('akses', function($query) {
            $query->where('akses', "user");
        })->when($this->idinstansi, function($q) {
            $q->whereHas('detailuser', function($query) {
                $query->where('idinstansi', $this->idinstansi);
            });
        })
        ->paginate(15);
       
        return view('livewire.user-live', [
            "user" => $user
        ]);
    }


    public function hapususer($iduser)
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

        $user = User::find($iduser);
        

        $user->akses()->delete();
        $user->detailuser()->delete();
        $user->delete();
        LivewireAlert::title('Success')->text("user berhasil dihapus.")->success()->show();
    }

   

    protected function validasi($iduser): bool
    {
        $error = false;
        $user = User::with('akses')->findOrFail($iduser);
        if($user->akses->akses == "pegawai") {
            $error = true;
        }
        if($user->akses->akses == "superadmin") {
            $error = true;
        }
        if($user->akses->akses == "admin") {
            $error = true;
        }
        if($user->detailuser->instansi->idinstansi != $this->idinstansi) {
            $error = true;
        }

        return $error;
    }

}
