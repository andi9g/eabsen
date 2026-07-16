<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pengaturanM extends Model
{
    protected $table = 'pengaturan';
    protected $primaryKey = 'idpengaturan';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
}
