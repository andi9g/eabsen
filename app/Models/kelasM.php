<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kelasM extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'idkelas';
    protected $guarded = [];
    protected $connection = 'mysql';
        
    //protected $fillable = ['name1','name2'];
        
    public function siswa()
    {
        return $this->hasMany(siswaM::class, 'idkelas', 'idkelas');
    }
}
