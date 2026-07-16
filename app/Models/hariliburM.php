<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class hariliburM extends Model
{
    protected $table = 'harilibur';
    protected $primaryKey = 'idharilibur';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function hari()
    {
        return $this->belongsTo(hariM::class, 'idhari', 'idhari');
    }
}
