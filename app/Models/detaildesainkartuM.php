<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class detaildesainkartuM extends Model
{
    protected $table = 'detaildesainkartu';
    protected $primaryKey = 'iddetaildesainkartu';
    protected $fillable = [
        'iddesainkartu',
        'desainkartu',
        'gambardepan',
        'gambarbelakang',
        'warnadepan',
        'warnabelakang',
        'warnatextdepan',
        'warnatextbelakang',
        'warnaborder',
        'tebalborder',
        'radiusborder'
    ];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function desainkartu()
    {
        return $this->belongsTo(desainkartuM::class, 'iddesainkartu', 'iddesainkartu');
    }

    protected static function boot()
    {
        parent::boot();
    
        // Event ini akan otomatis berjalan SETIAP KALI data model di-update
        static::updating(function ($model) {
            
            // Cek apakah kolom 'gambardepan' isinya berubah (isDirty)
            if ($model->isDirty('gambardepan')) {
                
                // Ambil nama file/path foto yang lama sebelum ditimpa
                $fotoLama = $model->getOriginal('gambardepan');
    
                // Jika foto lama ada di database dan file fisiknya ada di storage, hapus!
                if ($fotoLama && Storage::disk('s3')->exists($fotoLama)) {
                    Storage::disk('s3')->delete($fotoLama);
                }
            }
            if ($model->isDirty('gambarbelakang')) {
                
                // Ambil nama file/path foto yang lama sebelum ditimpa
                $fotoLama = $model->getOriginal('gambarbelakang');
    
                // Jika foto lama ada di database dan file fisiknya ada di storage, hapus!
                if ($fotoLama && Storage::disk('s3')->exists($fotoLama)) {
                    Storage::disk('s3')->delete($fotoLama);
                }
            }
        });
    }
    
    
}
