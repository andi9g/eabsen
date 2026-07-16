<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Models\socialiteM;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Session;
use DB;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();
        
        $socialite = Session::has("socialite")??false;
        // dd($socialite);
        
        // Gunakan DB::transaction agar proses pendaftaran user dan socialite sinkron (jika satu gagal, semua dibatalkan)   
        return DB::transaction(function () use ($input, $socialite) {

            // 1. Buat User baru (Kondisi verifikasi ditentukan langsung di sini)
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => $input['password'],
                'is_default_password' => $socialite ? 0 : 1, 
                'email_verified_at'   => $socialite ? now() : null, 
            ]);

            if ($socialite) {
                $array_merge = array_merge($user->toArray(), Session::get('socialite'));
                socialiteM::create($array_merge);
                Session::forget('socialite');
            }

            return $user;
        });
    }
}
