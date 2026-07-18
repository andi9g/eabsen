<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use App\Http\Controllers\SocialiteC;
use App\Http\Controllers\superadminC;
use App\Http\Controllers\PwaController;
use App\Http\Controllers\pegawaiC;
use App\Http\Controllers\adminC;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;


    if (app()->environment('local')) {
        Route::post('/_boost/browser-logs', function () {
            return response()->json(['status' => 'ignored']);
        })->name("loger");
    }

    
    Route::get('/disk-s3/{nama_file}', function ($namaFile) {
        $fullPath = $namaFile; // Sesuaikan jika ada sub-folder statis seperti 'absen/' . $namaFile
    
        if (Storage::disk('s3')->exists($fullPath)) {
            $file = Storage::disk('s3')->get($fullPath);
            $type = Storage::disk('s3')->mimeType($fullPath);
    
            return Response::make($file, 200, [
                'Content-Type' => $type,
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }
    
        abort(404);
    })->where('nama_file', '.*');

   
    
    Route::get('link/daftar/{kode}', [pegawaiC::class, 'daftar'])->name('link.daftar');
    Route::view('/', 'welcome')->name('home');
    
    Route::get('/auth/redirect', [SocialiteC::class, 'socialite'])->name('auth.socialite');
    Route::get('/auth/google/callback', [SocialiteC::class, 'callback'])->name('auth.callback');
    
    Route::get('/manifest.json', [PwaController::class, 'manifest'])->name('pwa.manifest');

    Route::middleware(['auth', 'verified', 'BuatAkses', "sessionku"])->group(function () {
        
        Route::view('dashboard', 'dashboard')->name('dashboard');
        Route::get('/auth/redirect/change', [SocialiteC::class, 'socialitechange'])->name('auth.socialite.change');
        Route::get('datadiri', [pegawaiC::class, 'datadiri'])->name("datadiri");
        Route::get('download', [PwaController::class, 'download'])->name('pwa.download');

        Route::middleware(['admin'])->group(function () {
            Route::get('admin', [superadminC::class, "admin"])->name('admin');
            Route::get('pegawai', [adminC::class, "pegawai"])->name('pegawai');
            Route::get('user', [adminC::class, "user"])->name('user');
            Route::get('walikelas', [adminC::class, "walikelas"])->name('walikelas')->middleware("AksesPegawai");
            
            //pengaturan
            Route::get('perangkat', [adminC::class, "perangkat"])->name('perangkat');
            Route::get('jamoperasional', [adminC::class, "jamoperasional"])->name('jamoperasional'); 
            Route::get('instansi', [adminC::class, "instansi"])->name('instansi'); 
            Route::get('semester', [adminC::class, "semester"])->name('semester'); 
            
            //registrasi
            Route::get('registerasi', [adminC::class, "registerasi"])->name('registerasi');
    
            //Data Siswa
            Route::get('import', [adminC::class, "import"])->name('import');
            Route::get('siswa', [adminC::class, "siswa"])->name('siswa');
            Route::get('rombel', [adminC::class, "rombel"])->name('rombel');
    
            //desainkartu
            Route::get('desain-kartu', [adminC::class, 'desainkartu'])->name("desainkartu");
        });
        
    
        Route::middleware(['pegawai'])->group(function () {
            Route::get("absensiswa", [pegawaiC::class, 'absensiswa'])->name("absensiswa");
        });
        Route::middleware(['wakadankepsek'])->group(function () {
            Route::get("cetak/absensiswa", [pegawaiC::class, 'cetakabsensiswa'])->name("cetak.absensiswa");
            Route::get("cetak/absensiswa/cetak", [pegawaiC::class, 'cetaklaporanabsensiswa'])->name("laporanabsensiswa.cetak");
        });    
        Route::middleware(['superadmin'])->group(function () {
            Route::get("cetak-kartu", [superadminC::class, 'cetakkartu'])->name("cetakkartu");
            Route::get("cetak-kartu/{idkelas}/{idjurusan}/{idinstansi}", [superadminC::class, 'cetakkartupdf'])->name("cetak.kartu");
        });
            
    
    });
    
    require __DIR__.'/settings.php';
