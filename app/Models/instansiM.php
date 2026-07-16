<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class instansiM extends Model
{
    protected $table = 'instansi';
    protected $primaryKey = 'idinstansi';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];


        
    public function detailuser()
    {
        return $this->hasOne(detailuserM::class, 'idinstansi', 'idinstansi');
    }
    public function perangkat()
    {
        return $this->hasMany(perangkatM::class, 'idinstansi', 'idinstansi');
    }
    public function siswa()
    {
        return $this->hasMany(siswaM::class, 'idinstansi', 'idinstansi');
    }
    public function kartu()
    {
        return $this->hasMany(kartuM::class, 'idinstansi', 'idinstansi');
    }
    public function walikelas()
    {
        return $this->hasMany(walikelasM::class, 'idinstansi', 'idinstansi');
    }
    public function semester()
    {
        return $this->hasMany(semesterM::class, 'idinstansi', 'idinstansi');
    }
    public function semesteraktif()
    {
        return $this->hasOne(semesteraktifM::class, 'idinstansi', 'idinstansi');
    }
    public function pengaturan()
    {
        return $this->hasOne(pengaturanM::class, 'idinstansi', 'idinstansi');
    }
    public function absensiswa()
    {
        return $this->hasMany(absensiswaM::class, 'idinstansi', 'idinstansi');
    }
    public function registrasilink()
    {
        return $this->hasOne(registrasilinkM::class, 'idinstansi', 'idinstansi');
    }



    
    
    protected static function booted()
    {
        static::updating(function ($model) {
            // cek apakah field gambar berubah
            if ($model->isDirty('logo')) {
                $oldImage = $model->getOriginal('logo');
    
                // hapus file lama jika ada
                if ($oldImage && Storage::disk('s3')->exists($oldImage)) {
                    Storage::disk('s3')->delete($oldImage);
                }
            }
        });
    
        static::deleting(function ($model) {
            // hapus juga saat data dihapus
            if ($model->logo && Storage::disk('s3')->exists($model->logo)) {
                Storage::disk('s3')->delete($model->logo);
            }
        });
    }
}
