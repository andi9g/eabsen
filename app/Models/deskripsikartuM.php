<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class deskripsikartuM extends Model
{
    protected $table = 'deskripsikartu';
    protected $primaryKey = 'iddeskripsikartu';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function desainkartu()
    {
        return $this->belongsTo(desainkartuM::class, 'iddesainkartu', 'iddesainkartu');
    }
}
