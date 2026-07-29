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
use App\Models\detailuserM;
use App\Models\aksesM;
use App\Models\registrasilinkM;
use Str;

class PegawaiLive extends Component
{
    use WithPagination;

    #[Locked]
    public $iduser, $idinstansi, $npsn;

    public $search, $data, $dataPosisi;
    public $email, $name;

    public $link;
    public function mount()
    {
        $this->dataPosisi = [
            "pegawai" => "Pegawai",
            "waka" => "Waka",
            "kepsek" => "Kepala Sekolah",
            "tu" => "Tata Usaha",
            "admin" => "Admin",
        ];
        $this->iduser = auth()->user()->iduser;
        $this->idinstansi = session()->get("idinstansi");
        $this->npsn = auth()->user()->detailuser->instansi->npsn ?? '';
        $this->link = registrasilinkM::where([
            "idinstansi" => $this->idinstansi,
            "akses" => "pegawai",
        ])->first();

        $this->posisi();
        
               
    }

    public function posisi()
    {
        $this->data = User::whereHas('akses', function($query) {
            $query->where(function ($q) {
                $q->where('akses', "pegawai")
                    ->orWhere("akses", "waka")
                    ->orWhere("akses", "kepsek")
                    ->orWhere("akses", "tu")
                    ->orWhere("akses", "admin");
            });
        })->when($this->idinstansi, function($q) {
            $q->whereHas('detailuser', function($query) {
                $query->where('idinstansi', $this->idinstansi);
            });
        })
        ->with("akses")
        ->get()->pluck("akses.akses", "iduser")->toArray();
    }

    public function updateposisi()
    {
        $dataKey = array_keys($this->data);
        $dataValid = detailuserM::whereIn("iduser", $dataKey)
        ->where("idinstansi", $this->idinstansi)
        ->get()
        ->pluck("iduser")->toArray();
        
        //hanya mangambil data dari yang valid, array flip hanya id saja
        $arrayBersih = array_intersect_key($this->data, array_flip($dataValid));
        $dataUpdate = [];
        foreach ($arrayBersih as $key => $value) {
            $dataUpdate[] = [
                "iduser" => $key,
                "akses" => $value,
            ];
        }

        $data = aksesM::upsert(
            $dataUpdate,
            ['iduser'],
            ['akses']
        );

        LivewireAlert::title('Success')->success()->show();
        $this->posisi();
    }

    public function render()
    {
        $pegawai = User::when($this->search, function($q) {
            $q->where(function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        })->whereHas('akses', function($query) {
            $query->where(function ($q) {
                $q->where('akses', "pegawai")
                    ->orWhere("akses", "waka")
                    ->orWhere("akses", "kepsek")
                    ->orWhere("akses", "tu")
                    ->orWhere("akses", "admin");
            });
            
        })->when($this->idinstansi, function($q) {
            $q->whereHas('detailuser', function($query) {
                $query->where('idinstansi', $this->idinstansi);
            });
        })->join("akses", "users.iduser", "=", "akses.iduser")
        ->with("akses")
        ->orderByRaw("FIELD(akses.akses, 'kepsek', 'waka', 'pegawai')")
        // ->orderBy("akses.akses", "asc")
        ->paginate(15);
       
        return view('livewire.pegawai-live', [
            "pegawai" => $pegawai
        ]);
    }

    public function tomboltambahpegawai()
    {
        Flux::modal('tomboltambahpegawai')->show();
    }

    public function daftar()
    {
        Flux::modal('linkdaftar')->show();
    }

    public function hapuslink()
    {
        $hapus = registrasilinkM::where("idinstansi", $this->idinstansi)->delete();    
        $this->link = registrasilinkM::where([
            "idinstansi" => $this->idinstansi,
            "akses" => "pegawai",
        ])->first();
    }

    public function generatelink()
    {
        $kode = Str::uuid()->toString();
        $update = registrasilinkM::where("idinstansi", $this->idinstansi)->first();
        $update->update([
            "kode" => $kode,
        ]);
        $this->link = $update;
        Flux::toast(
            heading:'Alert',
            text:'Generate success',
            variant:'success',
        );
    }
    public function buatlink()
    {
        $kode = Str::uuid()->toString();

        $link = registrasilinkM::firstOrCreate([
            "idinstansi" => $this->idinstansi,
            "akses" => "pegawai",
        ], [
            "status" => true,
            "kode" => $kode,
        ]);

        $this->link = $link->first();
    }

    public function tambahpegawai()
    {
        $npsn = "";
        
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ],[
            "required" => "Field wajib di isi.",
            "email.unique" => "Email sudah digunakan.",
        ]);

        $user = User::create([
            "name" => $this->name,
            "email" => $this->email,
            "email_verified_at" => now(),
            "is_default_password" => true,
            "password" => bcrypt($this->npsn),
        ]);

        if($user) {
            $user->akses()->create([
                "akses" => "pegawai"
            ]);
            $user->detailuser()->create([
                "idinstansi" => $this->idinstansi
            ]);
        }


        
        Flux::modals()->close();
        LivewireAlert::title('Success')->title("Password NPSN: " . $this->npsn)->success()->show();
    }

    public function hapuspegawai($iduser)
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
        LivewireAlert::title('Success')->text("pegawai berhasil dihapus.")->success()->show();
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
        
        $user = User::find($iduser);

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
