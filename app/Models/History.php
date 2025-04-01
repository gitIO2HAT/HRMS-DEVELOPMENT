<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    public $table = 'history';
    protected $fillable = [
        'history_id',
        'period', 
        'particular', 
        'v_earned', 
        'v_wp', 
        'v_balance', 
        'v_wop', 
        's_earned', 
        's_wp', 
        's_balance', 
        's_wop', 
        'date_action'
    ];

    public function user(){

        return $this->belongsTo(User::class, 'history_id', 'custom_id');
    }
   
}
