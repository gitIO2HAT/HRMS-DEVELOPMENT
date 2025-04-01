<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use App\Models\Message;
use Pusher\Pusher;
use Illuminate\Support\Facades\DB;
use App\Events\TaskCreated;
use Illuminate\Support\Facades\Http;
use App\Rules\AfterOrEqualStart;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    public function announcement(Request $request)
    {
        $notification['notify'] = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
        ->selectRaw('COUNT(messages.is_read) AS unread')
        ->selectRaw('COUNT(messages.inbox) AS inbox')
        ->leftJoin('messages', function($join) {
            $join->on('users.id', '=', 'messages.send_to')
                 ->where('messages.inbox', '=', 0);
        })
        ->where('users.id', Auth::id())
        ->groupBy('users.id', 'users.name', 'users.lastname', 'users.email')
        ->get();
    


date_default_timezone_set('Asia/Manila');

// Get the current date and time in Asia/Manila timezone
$currentDateTime['currentDateTime'] = Carbon::now()->setTimezone('Asia/Manila');

        $users['users'] = User::all();

        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();



        $query = Task::getTask();
        $getAnn['getAnn'] = $query->orderby('scheduled_date', 'asc')->paginate(10);

        $query = Task::getCompleted();
        $getCompleted['getCompleted'] = $query->orderby('scheduled_date', 'asc')->paginate(10);


        if(Auth::user()->user_type === 0){
        $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
        ->where('user_type', '!=', 0)
        ->groupBy('year')
        ->pluck('total', 'year')
        ->toArray();

        $employeeCount = User::where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->where('user_type', '!=', 0)
        ->count();

    $employeefemale = User::where('sex', '=', 'Female')
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employeemale = User::where('sex', '=', 'Male')
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee1822 = User::whereBetween('age', [18, 22])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee2327 = User::whereBetween('age', [23, 27])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee2833 = User::whereBetween('age', [28, 33])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee3438 = User::whereBetween('age', [34, 38])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee3943 = User::whereBetween('age', [39, 43])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee4448 = User::whereBetween('age', [44, 48])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee4953 = User::whereBetween('age', [49, 53])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee5460 = User::whereBetween('age', [54, 60])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();

    $departmentCounts = User::select('department', DB::raw('count(*) as total'))
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->groupBy('department')
        ->get();

    // Prepare data for Chart.js
    $departments = $departmentCounts->pluck('department');
    $counts = $departmentCounts->pluck('total');

    $totalEmployeesAtStart = User::where('date_of_assumption', '<=', now()->startOfYear())
    ->where('user_type', '!=', 0)->count();
    $employeesStayed = User::where('is_archive', 1)
    ->where('user_type', '!=', 0)->count();

    // Handle division by zero
    if ($totalEmployeesAtStart > 0) {
        // Calculate retention rate
        $retentionRate = ($employeesStayed / $totalEmployeesAtStart) * 100;
    } else {
        // Set retention rate to 0 or handle it differently
        $retentionRate = 0;
    }
$totalEmployeesAtEnd = User::count();
// Calculate the number of employees who have left (assuming archived employees have left)
$employeesLeft = User::where('is_archive', 2)
->where('user_type', '!=', 0)
->count();

// Calculate the average number of employees
if ($totalEmployeesAtStart + $totalEmployeesAtEnd > 0) {
    $averageEmployees = ($totalEmployeesAtStart + $totalEmployeesAtEnd) / 2;
} else {
    $averageEmployees = 0;
}

// Handle division by zero
if ($averageEmployees > 0) {
    // Calculate turnover rate
    $turnoverRate = ($employeesLeft / $averageEmployees) * 100;
} else {
    $turnoverRate = 0;
}
    }else{
        $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
        ->whereNotIn('user_type', [0, 1])
        ->groupBy('year')
        ->pluck('total', 'year')
        ->toArray();
        $employeeCount = User::where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->where('user_type', '!=', 0)
        ->count();

    $employeefemale = User::where('sex', '=', 'Female')
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employeemale = User::where('sex', '=', 'Male')
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee1822 = User::whereBetween('age', [18, 22])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee2327 = User::whereBetween('age', [23, 27])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee2833 = User::whereBetween('age', [28, 33])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee3438 = User::whereBetween('age', [34, 38])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee3943 = User::whereBetween('age', [39, 43])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee4448 = User::whereBetween('age', [44, 48])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee4953 = User::whereBetween('age', [49, 53])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();
    $employee5460 = User::whereBetween('age', [54, 60])
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->count();

    $departmentCounts = User::select('department', DB::raw('count(*) as total'))
        ->where('is_archive', 1)
        ->where('user_type', '!=', 0)
        ->groupBy('department')
        ->get();

    // Prepare data for Chart.js
    $departments = $departmentCounts->pluck('department');
    $counts = $departmentCounts->pluck('total');

    $totalEmployeesAtStart = User::where('date_of_assumption', '<=', now()->startOfYear())
    ->where('user_type', '!=', 0)->count();
    $employeesStayed = User::where('is_archive', 1)
    ->where('user_type', '!=', 0)->count();

    // Handle division by zero
    if ($totalEmployeesAtStart > 0) {
        // Calculate retention rate
        $retentionRate = ($employeesStayed / $totalEmployeesAtStart) * 100;
    } else {
        // Set retention rate to 0 or handle it differently
        $retentionRate = 0;
    }
$totalEmployeesAtEnd = User::count();
// Calculate the number of employees who have left (assuming archived employees have left)
$employeesLeft = User::where('is_archive', 2)
->where('user_type', '!=', 0)
->count();

// Calculate the average number of employees
if ($totalEmployeesAtStart + $totalEmployeesAtEnd > 0) {
    $averageEmployees = ($totalEmployeesAtStart + $totalEmployeesAtEnd) / 2;
} else {
    $averageEmployees = 0;
}

// Handle division by zero
if ($averageEmployees > 0) {
    // Calculate turnover rate
    $turnoverRate = ($employeesLeft / $averageEmployees) * 100;
} else {
    $turnoverRate = 0;
}
    }

// Calculate growth rate for each year
$growthRates = [];
$years = array_keys($employeeData);
for ($i = 1; $i < count($years); $i++) {
$previousYearEmployees = $employeeData[$years[$i - 1]];
$currentYearEmployees = $employeeData[$years[$i]];
$growthRate = (($currentYearEmployees - $previousYearEmployees) / $previousYearEmployees) * 100;
$growthRates[$years[$i]] = $growthRate;
}



        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Announcement.Announcement'
            : (Auth::user()->user_type == 1
                ? 'Admin.Announcement.Announcement'
                : 'Employee.Dashboard');


        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
            'getAnn' => $getAnn['getAnn'],
            'getCompleted' => $getCompleted['getCompleted'],
            'currentDateTime' => $currentDateTime['currentDateTime'],
            'users' => $users['users'],
            'growthRates' => $growthRates,
            'employeeData' => $employeeData,
            'employeefemale' => $employeefemale,
            'employeemale' => $employeemale,
            'employee1822' => $employee1822,
            'employee2327' => $employee2327,
            'employee2833' => $employee2833,
            'employee3438' => $employee3438,
            'employee3943' => $employee3943,
            'employee4448' => $employee4448,
            'employee4953' => $employee4953,
            'employee5460' => $employee5460,
            'departments' => $departments,
            'counts' => $counts,
            'employeesStayed' => $employeesStayed,
            'totalEmployeesAtStart' => $totalEmployeesAtStart,
            'retentionRate' => $retentionRate,
            'averageEmployees' => $averageEmployees,
            'employeesLeft' => $employeesLeft,
            'turnoverRate' => $turnoverRate // Access the 'users' array directly
        ]);
    }

    public function read($id)
    {
        $read = Message::getID($id);
        $read->inbox = 1;
        $read->save();
        return redirect()->back();
    }

    public function save_task(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:50',
        'description' => 'required|string',
        'scheduled_date' => 'nullable|date',
        'scheduled_end' => [
            'nullable',
            'date',
            new AfterOrEqualStart($request->input('scheduled_date')),
        ],
        'selected_users' => 'nullable|array',
    ], [
        'title.required' => 'The title field is required.',
        'description.required' => 'The description field is required.',
        'scheduled_end.after_or_equal_start' => 'The end date and time must be after or equal to the start date and time.',
    ]);

    $task = new Task;
    $task->title = $request->title;
    $task->description = $request->description;
    $task->scheduled_date = $request->input('scheduled_date');
    $task->scheduled_end = $request->input('scheduled_end');

    // Check if specific users are selected
    $selectedUsers = $request->input('selected_users', []);

    // Get users based on selection
    $users = $selectedUsers ? User::whereIn('id', $selectedUsers)->get() : User::all();

    $notificationSent = false;

    foreach ($users as $user) {
        $message = new Message;
        $message->send_to = $user->id;
        $message->from = Auth::user()->name;
        $message->profile_pic = Auth::user()->profile_pic;
        $message->title_message = $task->title;
        $message->description_message = $task->description;

        // Send notification only if there is an internet connection
        if ($this->checkInternetConnection()) {
            $options = [
                'cluster' => 'us2',
                'useTLS' => true,
            ];

            $pusher = new Pusher(
                env('PUSHER_APP_KEY'),
                env('PUSHER_APP_SECRET'),
                env('PUSHER_APP_ID'),
                $options
            );

            $data = ['send_to' => $user->id];
            $pusher->trigger('my-channel', 'my-event', $data);
            $notificationSent = true;
        }

        // Save message to the database only if notification was sent successfully
        if ($notificationSent) {
            $message->save();
        }
    }

    // Save task only if notification was sent successfully
    if ($notificationSent) {
        $task->save();
        broadcast(new TaskCreated($task, $message));
        return redirect()->back()->with('success', 'Announcement successfully sent');
    } else {
        return redirect()->back()->with('error', 'No internet connection');
    }
}

private function checkInternetConnection()
{
    try {
        $response = Http::get('https://www.google.com');
        return $response->successful();
    } catch (\Exception $e) {
        return false;
    }
}



}
