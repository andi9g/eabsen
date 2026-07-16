<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class absensiswaM extends Model
{
    protected $table = 'absensiswa';
    protected $primaryKey = 'idabsensiswa';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
    public function siswa()
    {
        return $this->belongsTo(siswaM::class, 'idsiswa', 'idsiswa');
    }
}
