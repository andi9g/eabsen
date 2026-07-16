<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\socialiteM;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;
use Illuminate\Support\Facades\Crypt;
use Auth;
use Hash;
use Session;
use Str;

class SocialiteC extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function socialite()
    {
        $parameter = [];
        $state = Crypt::encryptString(json_encode($parameter));
        return Socialite::driver('google')
        ->with(['state' => $state])
        ->redirect();
    }
    
    public function socialitechange()
    {
        $parameter = [
            "status" => "change",
        ];

        $state = Crypt::encryptString(json_encode($parameter));
        return Socialite::driver('google')
        ->with(['state' => $state])
        ->redirect();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function callback(Request $request)
    {
        // try {
            
            $stateRaw = $request->input('state');
            $parameter = json_decode(Crypt::decryptString($stateRaw), true);
            $status = $parameter['status'] ?? null;

            
            $google = Socialite::driver('google')->stateless()->user();
            // dd($google->toArray());
            if($status == null) {
                $socialite = socialiteM::where("id", $google->id)->with("user")->whereHas("user");
                if($socialite->exists()){
                    Auth::login($socialite->first()->user);
                    return redirect()->intended("/dashboard");
                }else {
                    Session::put("socialite", [
                        "socialite" => true,
                        "id" => $google->id,
                        "email" => $google->email,
                        "avatar" => $google->avatar,
                        "name" => $google->name,
                    ]);
                    
                    return redirect('register');
                }
            }else if($status == "change") {
                $iduser = auth()->user()->iduser;
                socialiteM::updateOrCreate([
                    "iduser" => $iduser,
                ], [
                    "id" => $google->id,
                    "email" => $google->email,
                    "avatar" => $google->avatar,
                ]);

                return redirect('settings/akungoogle')->with("success", "akun google berhasil dihubungkan.");
            }
            // $login = User::where("email")
 
            // $user = User::firstOrCreate([
            //     'email' => $google->email,
            // ], [
            //     'name' => $google->name,
            //     'password' => Hash::make(Str::random(32)),
            //     'email_verified_at' => now(),
            //     'is_default_password' => true,
            // ]);
        
            // Auth::login($user);
        
            // return redirect()->intended("/dashboard");
        // } catch (\Throwable $th) {
        //     return redirect('login')->with("error", "Terjadi kesalahan");
        // }
        
    }

    // use Illuminate\Http\Request;
    // use Laravel\Socialite\Facades\Socialite;
    
    // public function socialite(Request $request)
    // {
    //     // 1. Definisikan parameter yang ingin dikirim
    //     $customParams = [
    //         'idinstansi' => $this->idinstansi, // contoh parameter kamu
    //         'redirect_to' => '/dashboard',
    //     ];
    
    //     // 2. Encode menjadi JSON lalu Base64 agar aman di URL
    //     $state = base64_encode(json_encode($customParams));
    
    //     // 3. Kirim ke Google menggunakan ->withState()
    //     return Socialite::driver('google')
    //         ->withState($state)
    //         ->redirect();
    // }
    
    // public function handleGoogleCallback(Request $request)
    // {
    //     // 1. Ambil state dari query string yang dikembalikan oleh Google
    //     $stateRaw = $request->input('state');
    
    //     if ($stateRaw) {
    //         // 2. Decode kembali menjadi array PHP
    //         $params = json_decode(base64_decode($stateRaw), true);
    
    //         // Sekarang kamu bisa akses nilainya!
    //         $idinstansi = $params['idinstansi'] ?? null;
    //         $redirectTo = $params['redirect_to'] ?? '/';
            
    //         // Contoh logika: Simpan ke session atau pakai langsung
    //         session(['idinstansi' => $idinstansi]);
    //     }
    
    //     // 3. Ambil data user Google seperti biasa
    //     $googleUser = Socialite::driver('google')->user();
    
    //     // 4. Lanjutkan proses login/register kamu...
    // }

}
