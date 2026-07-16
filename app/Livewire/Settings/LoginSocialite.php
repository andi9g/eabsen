<?php

namespace App\Livewire\Settings;

use App\Concerns\ProfileValidationRules;
use Flux\Flux;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Attributes\Locked;
use App\Models\socialiteM;

#[Title('Profile settings')]
class LoginSocialite extends Component
{
    use ProfileValidationRules;
    #[Locked]
    public $iduser;

    public $status;
    public function mount()
    {
        $this->iduser = auth()->user()->iduser;
        $this->status = socialiteM::where("iduser", $this->iduser)->first();
    }
    public function render()
    {
        return view('livewire.settings.login-socialite');
    }
}
