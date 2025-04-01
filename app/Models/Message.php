<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;


    public $table = 'messages';
    protected $fillable = [
        'sent_to',
        'from',
        'profile_pic',
        'title_message',
        'description_message',
        'is_read',
        'inbox',
    ];

    public static function getNotify()
{
    return self::where('send_to', '=', Auth::user()->id)
    ->where('is_read', '=', 0);
}
    static public function getID($id)
    {
        return self::find($id);
    }



}
