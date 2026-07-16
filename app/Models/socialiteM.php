<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class socialiteM extends Model
{
    protected $table = 'socialite';
    protected $primaryKey = 'idsocialite';
    // protected $guarded = [];
    protected $connection = 'mysql';
        
    protected $fillable = ['id','iduser', "email", "avatar"];
        
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'iduser');
    }
}
