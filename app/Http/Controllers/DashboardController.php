<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Department;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // TimeZoneDB API Details
        $apiUrl = "https://api.timezonedb.com/v2.1/get-time-zone";
        $apiKey = 'INQ8VCI2UGFC'; // Your TimeZoneDB API Key

        // Fetch current time in Asia/Manila timezone
        $response = Http::get($apiUrl, [
            'key' => $apiKey,
            'format' => 'json',
            'by' => 'zone',
            'zone' => 'Asia/Manila',
        ]);

        if ($response->successful()) {
            $currentDate = $response->json()['formatted'];
            $month = date('m', strtotime($currentDate));
            $day = date('d', strtotime($currentDate));

            // Fetch users whose birth date matches the current month and day
            $birthdayUsers = User::whereMonth('birth_date', $month)
                ->whereDay('birth_date', $day)
                ->where('is_archive', 1)
                ->where('user_type', '!=', 0)
                ->get();
        } else {
            // Handle the error response, perhaps log it or set an empty collection
            $birthdayUsers = collect(); // Empty collection
        }
        if (Auth::user()->user_type === 0) {


          

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


        } else {
            $employeeCount = User::where('is_archive', 1)
                ->whereNotIn('user_type', [0, 1])
                ->count();
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




        $departmentCount = Department::where('deleted', 1)
            ->count();

        if (Auth::user()->user_type === 0) {
            $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
                ->where('user_type', '!=', 0)
                ->groupBy('year')
                ->pluck('total', 'year')
                ->toArray();
        } else {
            $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
                ->whereNotIn('user_type', [0, 1])
                ->groupBy('year')
                ->pluck('total', 'year')
                ->toArray();
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

        date_default_timezone_set('Asia/Manila');

        // Get the current date and time in Asia/Manila timezone
        $currentDateTime['currentDateTime'] = Carbon::now()->setTimezone('Asia/Manila');
        // Notification query
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
    
        $gettask = Task::getTask();
        $getAnn['getAnn'] = $gettask->orderby('scheduled_date', 'asc')->paginate(10);

        $task = Task::getCompleted();
        $getCompleted['getCompleted'] = $task->orderby('scheduled_date', 'asc')->paginate(10);
        // Get notifications (latest 10)
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();

        // Determine the view path based on user type
        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Dashboard'
            : (Auth::user()->user_type == 1
                ? 'Admin.Dashboard'
                : 'Employee.Dashboard');

        // Pass variables to the view
        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
            'birthdayUsers' => $birthdayUsers,
            'employeeCount' => $employeeCount,
            'departmentCount' => $departmentCount,
            'getAnn' => $getAnn['getAnn'],
            'getCompleted' => $getCompleted['getCompleted'],
            'currentDateTime' => $currentDateTime['currentDateTime'],
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
            'turnoverRate' => $turnoverRate




            // Pass the birthday users to the view
        ]);
    }
}
