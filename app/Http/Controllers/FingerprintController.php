<?php

namespace App\Http\Controllers;

use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Message;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Carbon\Carbon;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Fingerprint;

class FingerprintController extends Controller
{
    public function fingerprint(Request $request)
    {
        // Get notifications
        $notification['notify'] = User::select('users.id', 'users.name', 'users.lastname', 'users.email')
            ->selectRaw('COUNT(messages.is_read) AS unread')
            ->selectRaw('COUNT(messages.inbox) AS inbox')
            ->leftJoin('messages', function ($join) {
                $join->on('users.id', '=', 'messages.send_to')
                    ->where('messages.inbox', '=', 0);
            })
            ->where('users.id', Auth::id())
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.email')
            ->get();

          
            $search = $request->input('search');

$FingerprintTable = Fingerprint::with('user');

if ($search) {
    $FingerprintTable->where(function ($query) use ($search) {
        $query->whereHas('user', function ($q) use ($search) {
            $q->whereRaw("CONCAT(users.name, ' ', users.lastname) LIKE ?", ["%$search%"]);
        })
        ->orWhere('status', 'LIKE', "%$search%")
        ->orWhere('user_id', 'LIKE', "%$search%");
    });
}

$FingerprintTable = $FingerprintTable->paginate(10);

            

        $query = Message::getNotify();

        $userId = Auth::user()->custom_id;
        $timezone = 'Asia/Manila';
        Carbon::setLocale('en'); // Optional: Set locale if needed
        $startOfWeek = Carbon::now($timezone)->startOfWeek();
        $endOfWeek = Carbon::now($timezone)->endOfWeek();

        $TodayMorning = Attendance::where('user_id', $userId)
            ->whereDate('created_at', '=', Carbon::today()) // Ensures it's for today
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_am_first, punch_in_am_second)) as total_minutes')
            ->value('total_minutes') ?? 0;

        $TodayAfternoon = Attendance::where('user_id', $userId)
            ->whereDate('created_at', '=', Carbon::today()) // Ensures it's for today
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_pm_first, punch_in_pm_second)) as total_minutes')
            ->value('total_minutes') ?? 0;



        $WeekMorning = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek]) // Use whereBetween for date range
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_am_first, punch_in_am_second)) as total_minutes')
            ->value('total_minutes');

        $WeekAfternoon = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek]) // Use whereBetween for date range
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_pm_first, punch_in_pm_second)) as total_minutes')
            ->value('total_minutes');

        $MonthMorning = Attendance::where('user_id', $userId)
            ->whereYear('date', Carbon::now()->year) // Use Carbon to get the current year
            ->whereMonth('date', Carbon::now()->month) // Use Carbon to get the current month
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_am_first, punch_in_am_second)) as total_minutes')
            ->value('total_minutes');

        $MonthAfternoon = Attendance::where('user_id', $userId)
            ->whereYear('date', Carbon::now()->year) // Use Carbon to get the current year
            ->whereMonth('date', Carbon::now()->month) // Use Carbon to get the current month
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_pm_first, punch_in_pm_second)) as total_minutes')
            ->value('total_minutes');

        //Undertime calculation
        $userAttendance = Attendance::select('user_id', 'date')
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_am_first, punch_in_am_second)) as total_am_minutes')
            ->selectRaw('SUM(TIMESTAMPDIFF(MINUTE, punch_in_pm_first, punch_in_pm_second)) as total_pm_minutes')
            ->groupBy('user_id', 'date')
            ->get();

        $attendanceData = $userAttendance->map(function ($attendance) {
            $totalMinutes = $attendance->total_am_minutes + $attendance->total_pm_minutes;
            return [
                'user_id' => $attendance->user_id,
                'date' => $attendance->date,
                'total_minutes' => $totalMinutes,
            ];
        });

        $TodayMinutes = $TodayMorning + $TodayAfternoon;
        $TodaySeconds = $TodayMinutes * 60;
        $TodayHours = floor($TodayMinutes / 60);
        $TodaysMinutes = $TodayMinutes  % 60;
        $Today = "{$TodayHours}:{$TodaysMinutes}";

        $WeekMinutes = $WeekMorning + $WeekAfternoon;
        $WeekSeconds = $WeekMinutes * 60;
        $WeekHours = floor($WeekMinutes / 60);
        $WeeksMinutes = $WeekMinutes  % 60;
        $Week = "{$WeekHours}:{$WeeksMinutes}";

        $MonthMinutes = $MonthMorning + $MonthAfternoon;
        $MonthSeconds = $MonthMinutes * 60;
        $MonthHours = floor($MonthMinutes / 60);
        $MonthsMinutes = $MonthMinutes  % 60;
        $Month = "{$MonthHours}:{$MonthsMinutes}";

        $MonthRemainingSeconds = 576000 - $MonthSeconds;
        $MonthRemainingMinutes = $MonthRemainingSeconds / 60;
        $MonthRemainingHours = floor($MonthRemainingMinutes / 60);
        $MonthRemainingsMinutes = $MonthRemainingMinutes  % 60;
        $MonthRemaining = "{$MonthRemainingHours}:{$MonthRemainingsMinutes}";


        // Get selected year and month from the request, or default to current year and month
        $selectedYear = $request->input('year', Carbon::now($timezone)->year);
        $selectedMonth = $request->input('month', Carbon::now($timezone)->month);

        // Generate a series of all days in the selected month of the selected year
        $startOfMonth = Carbon::create($selectedYear, $selectedMonth, 1, 0, 0, 0, $timezone)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        $allDays = [];
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $allDays[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        // Fetch daily series data
        $dailySeries = Attendance::selectRaw('DATE(date) as date, SUM(total_duration) as total_duration')
            ->where('user_id', $userId)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw('DATE(date)'))
            ->orderBy('date')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->date => $item->total_duration];
            })
            ->toArray();

        // Ensure all days have an entry, even if the total duration is zero
        $dailySeries = array_merge(array_fill_keys($allDays, 0), $dailySeries);

        // Get recent punches
        $getPunch = Attendance::where('user_id', $userId)->orderBy('created_at', 'desc')->take(10)->paginate(10);
        $getall = Attendance::with('user')
            ->where('user_id', '!=', 1)->orderBy('created_at', 'desc')->take(10)->paginate(10);
        // Search for employee records
        $search = $request->input('search');

        $employeeRecords = User::query();

        if ($search) {
            $employeeRecords->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(name, ' ', lastname) LIKE ?", ["%$search%"])
                    ->orWhere('custom_id', 'LIKE', "%$search%");
            });
        }


        if (Auth::user()->user_type === 0) {
            $users = user::where('user_type', '!=', 0)
                ->where('is_archive', 1)
                ->get();
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

            $usersadmin = user::where('user_type', 2)
                ->where('is_archive', 1)
                ->get();
        } else {
            $usersadmin = user::where('user_type', 2)
                ->where('is_archive', 1)
                ->get();

            $users = user::where('user_type', [0, 1])
                ->where('is_archive', 1)
                ->get();
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
        // Add the is_archive condition
        if (Auth::user()->user_type === 0) {
            $employeeRecords->where('is_archive', '=', 1)
                ->where('user_type', '!=', 0);
        } else {
            $employeeRecords->where('is_archive', '=', 1)
                ->where('user_type', '=', 2);;
        }

        $employeeRecords = $employeeRecords->paginate(10); // Apply pagination

        $RecordsAttendance = Attendance::all();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();
        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Fingerprint.Fingerprint'
            : (Auth::user()->user_type == 1
                ? 'Admin.Fingerprint.Fingerprint'
                : 'Employee.Dashboard');

        return view($viewPath, [
            'FingerprintTable' => $FingerprintTable,
            'notification' => $notification,
            'getNot' => $getNot,
            'Today' => $Today,
            'TodaySeconds' => $TodaySeconds,
            'Week' => $Week,
            'WeekSeconds' => $WeekSeconds,
            'Month' => $Month,
            'MonthSeconds' => $MonthSeconds,
            'MonthRemaining' => $MonthRemaining,
            'MonthRemainingSeconds' => $MonthRemainingSeconds,
            'attendanceData' => $attendanceData,
            'getPunch' => $getPunch,
            'employeeRecords' => $employeeRecords,
            'dailySeries' => $dailySeries,
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'RecordsAttendance' => $RecordsAttendance,
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
            'turnoverRate' => $turnoverRate,
            'getall' => $getall,
            'usersadmin' => $usersadmin,
            'users' => $users
        ]);
    }

    public function Active($id)
    {
        $fingerprintstatus = Fingerprint::find($id);

        if($fingerprintstatus->status === 'active'){
            return redirect()->back()->with('error', 'Fingerprint already ACTIVE');
        }else{
            $fingerprintstatus->status = 'active';
        }

        $fingerprintstatus->save();

        return redirect()->back()->with('success', 'Fingerprint successfully ACTIVE');
    }
    public function NotActive($id)
    {
        $fingerprintstatus = Fingerprint::find($id);
        if($fingerprintstatus->status === 'not active'){
            return redirect()->back()->with('error', 'Fingerprint already NOT ACTIVE');
        }else{
            $fingerprintstatus->status = 'not active';
        }
        $fingerprintstatus->save();

        return redirect()->back()->with('warning', 'Fingerprint successfully NOT ACTIVE');
    }
}
