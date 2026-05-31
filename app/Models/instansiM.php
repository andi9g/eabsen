<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class instansiM extends Model
{
    protected $table = 'instansi';
    protected $primaryKey = 'idinstansi';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function detailuser()
    {
        return $this->hasMany(detailuserM::class, 'idinstansi', 'idinstansi');
    }
}
