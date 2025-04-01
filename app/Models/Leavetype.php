<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leavetype extends Model
{
    use HasFactory;
    public $table = 'leavetype';
    protected $fillable = [
        'status', 
    ];

    public function leavestype()
    {
        return $this->hasMany(User::class);
    }

    

}
