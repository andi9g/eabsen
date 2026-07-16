<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class registrasilinkM extends Model
{
    protected $table = 'registrasilink';
    protected $primaryKey = 'idregistrasilink';
    // protected $guarded = [];
    protected $connection = 'mysql';
        
    protected $fillable = ['idinstansi','akses', 'kode', 'status'];

     public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
     
}
