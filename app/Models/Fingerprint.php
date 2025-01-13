<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fingerprint extends Model
{
    use HasFactory;

    public $table = 'user_fingerprint';
    protected $fillable = [
        'user_id',
        'template', 
        'status'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'user_id', 'custom_id');
    }
}
