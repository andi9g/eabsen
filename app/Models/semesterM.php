<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class semesterM extends Model
{
    protected $table = 'semester';
    protected $primaryKey = 'idsemester';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
    public function semesteraktif()
    {
        return $this->hasOne(semesteraktifM::class, 'idsemester', 'idsemester');
    }
}
