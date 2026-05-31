<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detailuserM extends Model
{
    protected $table = 'detailuser';
    protected $primaryKey = 'iddetailuser';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
    public function instansi()
    {
        return $this->belongsTo(instansiM::class, 'idinstansi', 'idinstansi');
    }
}
