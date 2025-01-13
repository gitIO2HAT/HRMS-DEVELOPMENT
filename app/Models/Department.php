<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    public $table = 'departments';
    protected $fillable = [
        'name',
        'deleted',
    ];

    public function positions()
    {
        return $this->hasMany(Position::class);
    }
    static public function getId($id)
    {
        return self::find($id);
    }
    public function users()
    {
        return $this->hasMany(User::class, 'department', 'id'); // Ensure this matches
    }


}
