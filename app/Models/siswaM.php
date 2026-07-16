<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class siswaM extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'idsiswa';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function kelas()
    {
        return $this->belongsTo(kelasM::class, 'idkelas', 'idkelas');
    }

    public function jurusan()
    {
        return $this->belongsTo(jurusanM::class, 'idjurusan', 'idjurusan');
    }
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
    public function kartu()
    {
        return $this->hasOne(kartuM::class, 'idsiswa', 'idsiswa');
    }
    public function absensiswa()
    {
        return $this->hasMany(absensiswaM::class, 'idsiswa', 'idsiswa');
    }
}
