<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Socialite;
use Auth;
use Hash;
use Str;

class SocialiteC extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function socialite()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function callback()
    {
        $google = Socialite::driver('google')->user();
 
        $user = User::firstOrCreate([
            'email' => $google->email,
        ], [
            'name' => $google->name,
            'password' => Hash::make(Str::random(32)),
            'email_verified_at' => now(),
            'is_default_password' => true,
        ]);
    
        Auth::login($user);
    
        return redirect('/dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
