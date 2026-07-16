<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class desainkartuM extends Model
{
    protected $table = 'desainkartu';
    protected $primaryKey = 'iddesainkartu';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function datadesainkartu()
    {
        return $this->hasMany(datadesainkartuM::class, 'iddesainkartu', 'iddesainkartu');
    }
    public function detaildesainkartu()
    {
        return $this->hasOne(detaildesainkartuM::class, 'iddesainkartu', 'iddesainkartu');
    }
    public function deskripsikartu()
    {
        return $this->hasOne(deskripsikartuM::class, 'iddesainkartu', 'iddesainkartu');
    }
}
