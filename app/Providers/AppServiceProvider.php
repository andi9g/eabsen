<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // // 1. Cek apakah request datang dari proxy/cloudflare dengan HTTPS
        // // Cloudflare selalu mengirimkan header X-Forwarded-Proto
        // if (Request::header('X-Forwarded-Proto') === 'https') {
        //     URL::forceScheme('https');
        // }
        
        // // 2. (Opsional) Jika Anda menggunakan Laravel 11, pastikan internal proxy di-trust 
        // // agar header di atas terbaca dengan benar oleh Laravel
        // if (isset($this->app['request'])) {
        //     $this->app['request']->setTrustedProxies(
        //         ['127.0.0.1', '::1'], // IP localhost/cloudflared tunnel
        //         \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR |
        //         \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO |
        //         \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST
        //     );
        // }
        // Cek apakah request datang dari Cloudflare (https)
        if (request()->header('X-Forwarded-Proto') === 'https') {
            
            // 1. Paksa Laravel menggunakan URL https
            URL::forceScheme('https');
            
            // 2. INI KUNCI UNTUK LIVEWIRE 3: 
            // Paksa server state menjadi HTTPS 'on' agar Livewire tidak menggunakan http://
            request()->server->set('HTTPS', 'on'); 
        }
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
