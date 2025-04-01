<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    public $table = 'tasks';
    protected $fillable = [
        'title',
        'scheduled_date',
        'scheduled_end',
        'description',
    ];

    public static function getTask()
    {
        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');

        // Get the current date and time in Asia/Manila timezone
        $currentDateTimeInManila = Carbon::now()->setTimezone('Asia/Manila');

        // Modify the query to include tasks where the scheduled date is in the future
        return self::where('scheduled_end', '>', $currentDateTimeInManila->toDateTimeString());
       
    

    }

    public static function getCompleted()
    {
        // Set the timezone to Asia/Manila
        date_default_timezone_set('Asia/Manila');

        $currentDateTimeInManila = Carbon::now()->setTimezone('Asia/Manila');
        // Modify the query to include completed tasks (where scheduled date is in the past)
        return self::where('scheduled_end', '<', $currentDateTimeInManila->toDateTimeString());


    }
}
