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
use App\Models\registrasilinkM;
use App\Models\instansiM;
use App\Models\User;
use App\Models\aksesM;
use App\Models\detailuserM;
use Hash;
use Illuminate\Support\Facades\DB;

class DaftarLive extends Component
{
    #[Locked]
    public $instansi, $registrasi;

    public $data;
    public function mount($kode)
    {
        Auth::guard('web')->logout();
        $this->kode = $kode;
        $this->registrasi = registrasilinkM::where("kode", $kode)->first();
        if(!$this->registrasi) {
            abort(403);
        }

        $this->instansi = $this->registrasi->instansi;
        $this->data = [];
    }

    public function render()
    {
        return view('livewire.daftar-live');
    }

    public function tambahakun()
    {
        $this->validate([
            'data.name' => 'required',
            'data.email' => 'required|email|unique:users,email',
            'data.password' => 'required|min:8|same:data.ulangipassword',
            'data.ulangipassword' => 'required',
        ],[
            "required" => "Field wajib di isi.",
            "same" => "Password tidak sama.",
            "min" => "Minimal 8 karakter.",
            "unique" => "Email telah digunakan.",
        ]);

        $hashpass = Hash::make($this->data["password"]);
        $user = DB::transaction(function () use ($hashpass) {
            $user = User::create([
                "name" => $this->data["name"],
                "email" => $this->data["email"],
                "password" => $hashpass,
                "email_verified_at" => now(),
                "is_default_password" => 0,
            ]);

            detailuserM::updateOrCreate([
                "idinstansi" => $this->instansi->idinstansi,
                "iduser" => $user->iduser,
            ]);

            aksesM::updateOrCreate([
                "iduser" => $user->iduser,
                "akses" => $this->registrasi->akses,
            ]);

            return $user;
        });

        if($user) {
            session()->flash('success', 'Akun berhasil dibuat! Silakan masuk.');
            return $this->redirectRoute('login');
        }

        LivewireAlert::title('error')->error()->show();


    }
}
