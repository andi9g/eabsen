<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class walikelasM extends Model
{
    protected $table = 'walikelas';
    protected $primaryKey = 'idwalikelas';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
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
    public function semester()
    {
        return $this->belongsTo(semesterM::class, 'idsemester', 'idsemester');
    }
}
