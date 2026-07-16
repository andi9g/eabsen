<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hariM extends Model
{
    protected $table = 'hari';
    protected $primaryKey = 'idhari';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function harilibur()
    {
        return $this->hasMany(hariliburM::class, 'idhari', 'idhari');
    }
}
