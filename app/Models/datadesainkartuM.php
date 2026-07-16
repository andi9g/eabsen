<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class datadesainkartuM extends Model
{
    protected $table = 'datadesainkartu';
    protected $primaryKey = 'iddatadesainkartu';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function desainkartu()
    {
        return $this->belongsTo(desainkartuM::class, 'iddesainkartu', 'iddesainkartu');
    }
}
