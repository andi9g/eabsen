<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class perangkatM extends Model
{
    protected $table = 'perangkat';
    protected $primaryKey = 'idperangkat';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
}
