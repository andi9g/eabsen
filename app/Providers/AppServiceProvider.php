<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

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
        // 1. Cek apakah request datang dari proxy/cloudflare dengan HTTPS
        // Cloudflare selalu mengirimkan header X-Forwarded-Proto
        if (Request::header('X-Forwarded-Proto') === 'https') {
            URL::forceScheme('https');
        }
        
        // 2. (Opsional) Jika Anda menggunakan Laravel 11, pastikan internal proxy di-trust 
        // agar header di atas terbaca dengan benar oleh Laravel
        if (isset($this->app['request'])) {
            $this->app['request']->setTrustedProxies(
                ['127.0.0.1', '::1'], // IP localhost/cloudflared tunnel
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_FOR |
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_PROTO |
                \Symfony\Component\HttpFoundation\Request::HEADER_X_FORWARDED_HOST
            );
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
