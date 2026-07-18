<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
       \Carbon\Carbon::setLocale('id');

        // 1. Ambil data mentah header CF-Visitor dari array $_SERVER
        $cfVisitor = $_SERVER['HTTP_CF_VISITOR'] ?? '';

        // 2. Deteksi apakah skemanya mengandung "https" ATAU diakses lewat domain sekolah
        $isHttps = str_contains($cfVisitor, '"scheme":"https"') || 
                (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'abseniot.smkn1gunungkijang.sch.id');

        if ($isHttps) {
            // Paksa skema URL Laravel ke HTTPS
            \Illuminate\Support\Facades\URL::forceScheme('https');
            
            // Paksa internal request PHP menganggap koneksinya adalah HTTPS (Kunci untuk Livewire 3)
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
