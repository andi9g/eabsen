<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jurusanM extends Model
{
    protected $table = 'jurusan';
    protected $primaryKey = 'idjurusan';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function siswa()
    {
        return $this->hasMany(siswaM::class, 'idjurusan', 'idjurusan');
    }
}
