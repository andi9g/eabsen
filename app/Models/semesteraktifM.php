<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class semesteraktifM extends Model
{
    protected $table = 'semesteraktif';
    protected $primaryKey = 'idsemesteraktif';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function semester()
    {
        return $this->belongsTo(semesterM::class, 'idsemester', 'idsemester');
    }
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
}
