<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kartuM extends Model
{
    protected $table = 'kartu';
    protected $primaryKey = 'idkartu';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function siswa()
    {
        return $this->belongsTo(siswaM::class, 'idsiswa', 'idsiswa');
    }
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
}
