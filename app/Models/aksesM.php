<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class aksesM extends Model
{
    protected $table = 'akses';
    protected $primaryKey = 'idakses';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function User()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
