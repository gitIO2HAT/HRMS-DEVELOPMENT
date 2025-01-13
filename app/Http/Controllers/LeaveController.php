<?php

namespace App\Http\Controllers;

use App\Exports\LeavesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use ZipArchive;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Illuminate\Support\Str;


use App\Models\Message;
use App\Models\Leave;
use App\Models\User;
use App\Models\Leavetype;
use App\Models\History;
use App\Models\Department;

class LeaveController extends Controller
{
    private $timeZoneDbApiKey = 'INQ8VCI2UGFC';
    public function leave(Request $request)
    {
        // Retrieve notifications
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

        // Retrieve notifications
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();

        $users = User::where('is_archive', 1);

        if (auth()->user()->user_type == 0) {
            // If the logged-in user's type is 0, fetch users whose type is not 0
            $users->where('user_type', '!=', 0);
        } elseif (auth()->user()->user_type == 1) {
            // If the logged-in user's type is 1, fetch users whose type is neither 0 nor 1
            $users->whereNotIn('user_type', [0, 1]);
        } elseif (auth()->user()->user_type == 2) {
            // If the logged-in user's type is 2, fetch users whose type is 2
            $users->where('user_type', 2);
        }

        // Get all the filtered users
        $users = $users->get();

        $search = request('search'); // Get the search term from the request
        if (auth()->user()->user_type == 0) {
            // If the logged-in user's type is 0, fetch users whose type is not 0
            $users->where('user_type', '!=', 0);

            $leaveData = Leave::where('deleted', 1)
            ->with('user', 'leavetype')// Exclude current user's leaves
            ->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%");
                })
                ->orWhereHas('leavetype', function ($query) use ($search) {
                    $query->where('status', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc') // Sort in descending order by created date
            ->paginate(10, ['*'], 'page_leave');
        } elseif (auth()->user()->user_type == 1) {
            $leaveData = Leave::where('deleted', 1)
            ->with('user', 'leavetype')
            ->where('employee_id', '!=', Auth::user()->custom_id) // Exclude current user's leaves
            ->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%");
                })
                ->orWhereHas('leavetype', function ($query) use ($search) {
                    $query->where('status', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc') // Sort in descending order by created date
            ->paginate(10, ['*'], 'page_leave');
            // If the logged-in user's type is 1, fetch users whose type is neither 0 nor 1
            $users->whereNotIn('user_type', [0, 1]);
        } elseif (auth()->user()->user_type == 2) {
            // If the logged-in user's type is 2, fetch users whose type is 2
            $users->where('user_type', 2);
        }
        
       
    



        $leavetype = Leavetype::All();


        if (Auth::user()->user_type === 0) {
            $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
                ->where('user_type', '!=', 0)
                ->groupBy('year')
                ->pluck('total', 'year')
                ->toArray();
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

        // Determine view path based on user type
        $viewPath = Auth::user()->user_type == 0
            ? 'superadmin.leave.leave'
            : (Auth::user()->user_type == 1
                ? 'admin.leave.leave'
                : 'employee.leave.leave');

        // Return the appropriate view
        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
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
            'users' => $users,
            'leavetype' => $leavetype,
            'leaveData' => $leaveData
        ]);
    }

    public function myleave(Request $request)
    {
        // Retrieve notifications
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

        // Retrieve notifications
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();

        $users = User::where('is_archive', 1);

        if (auth()->user()->user_type == 0) {
            // If the logged-in user's type is 0, fetch users whose type is not 0
            $users->where('user_type', '!=', 0);
        } elseif (auth()->user()->user_type == 1) {
            // If the logged-in user's type is 1, fetch users whose type is neither 0 nor 1
            $users->whereNotIn('user_type', [0, 1]);
        } elseif (auth()->user()->user_type == 2) {
            // If the logged-in user's type is 2, fetch users whose type is 2
            $users->where('user_type', 2);
        }

        // Get all the filtered users
        $users = $users->get();
        $search = request('search');

        $leaveData = Leave::where('deleted', 1)
            ->where('employee_id', Auth::user()->custom_id)
            ->with('user', 'leavetype')
            ->where(function ($query) use ($search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%");
                })
                    ->orWhereHas('leavetype', function ($query) use ($search) {
                        $query->where('status', 'like', "%{$search}%");
                    });
            })
            ->orderBy('created_at', 'desc') // Sort in descending order by created date
            ->paginate(10, ['*'], 'page_leave');



        $leavetype = Leavetype::All();


        if (Auth::user()->user_type === 0) {
            $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
                ->where('user_type', '!=', 0)
                ->groupBy('year')
                ->pluck('total', 'year')
                ->toArray();
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

        // Determine view path based on user type
        $viewPath = Auth::user()->user_type == 0
            ? 'superadmin.leave.myleave'
            : (Auth::user()->user_type == 1
                ? 'admin.leave.myleave'
                : 'employee.leave.myleave');

        // Return the appropriate view
        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
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
            'users' => $users,
            'leavetype' => $leavetype,
            'leaveData' => $leaveData
        ]);
    }

    public function credits(Request $request)
    {
        // Retrieve notifications
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

        // Retrieve notifications
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();

        $users = User::where('is_archive', 1);

        if (auth()->user()->user_type == 0) {

            $search = request('search');
            $users = User::where('is_archive', 1)
                ->where('user_type', '!=', 0)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%"); // Moved inside closure
                })
                ->orderBy('created_at', 'desc') // Sort in descending order by created date
                ->paginate(10, ['*'], 'page_user');
        } elseif (auth()->user()->user_type == 1) {
            // If the logged-in user's type is 1, fetch users whose type is neither 0 nor 1

            $search = request('search');
            $users = User::where('is_archive', 1)
                ->whereNotIn('user_type', [0, 1])
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%"); // Moved inside closure
                })
                ->orderBy('created_at', 'desc') // Sort in descending order by created date
                ->paginate(10, ['*'], 'page_user');
        } elseif (auth()->user()->user_type == 2) {
            // If the logged-in user's type is 2, fetch users whose type is 2

            $search = request('search');
            $users = User::where('is_archive', 1)
                ->where('user_type', 2)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('lastname', 'like', "%{$search}%")
                        ->orWhere('middlename', 'like', "%{$search}%"); // Moved inside closure
                })
                ->orderBy('created_at', 'desc') // Sort in descending order by created date
                ->paginate(10, ['*'], 'page_user');
        }

        // Get all the filtered users


        $leaveData = Leave::where('deleted', 1)->get();



        $leavetype = Leavetype::All();


        if (Auth::user()->user_type === 0) {
            $employeeData = User::selectRaw('YEAR(date_of_assumption) as year, COUNT(*) as total')
                ->where('user_type', '!=', 0)
                ->groupBy('year')
                ->pluck('total', 'year')
                ->toArray();
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

        // Determine view path based on user type
        $viewPath = Auth::user()->user_type == 0
            ? 'superadmin.leave.credit'
            : (Auth::user()->user_type == 1
                ? 'admin.leave.credit'
                : 'employee.leave.myleave');

        // Return the appropriate view
        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
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
            'users' => $users,
            'leavetype' => $leavetype,
            'leaveData' => $leaveData
        ]);
    }


    private function getInternetTime()
    {
        // Use the TimeZoneDB API to get the current time in Asia/Manila
        $response = Http::get('https://api.timezonedb.com/v2.1/list-time-zone', [
            'key' => $this->timeZoneDbApiKey,
            'format' => 'json',
            'zone' => 'Asia/Manila',
            'fields' => 'zoneName,gmtOffset'
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $gmtOffset = $data['zones'][0]['gmtOffset'];
            return Carbon::now()->utc()->addSeconds($gmtOffset);
        }

        throw new \Exception('Unable to retrieve time information.');
    }


    public function addleave(Request $request)
    {

        $leave = new Leave;

        $request->validate([
            'employee_id' => 'required|string|exists:users,custom_id',
            'leave_type' => 'required|exists:leavetype,id',
            'from' => 'required|date|before_or_equal:to',
            'to' => 'required|date|after_or_equal:from',
            'leave_days' => 'required|numeric|min:1',
            'details_leave' => 'nullable|in:1,2',
            'details_leave_sick' => 'nullable|in:In Hospital,Out Patient',
            
            'abroad' => 'nullable|string',
            'monetization' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'terminal' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',
            'adoption' => 'nullable|file|mimes:jpg,jpeg,png|max:20480',

        ], [
            'employee_id.required' => 'Employee is required.',
            'leave_type.required' => 'Leave type is required.',
            'from.required' => 'Inclusive from date is required.',
            'to.required' => 'Inclusive to date is required.',
            'leave_days.required' => 'Number of leave days must be calculated.',
            'monetization.mimes' => 'The file must be a file of type: jpg, jpeg, png.',
            'monetization.max' => 'The file size must not exceed 20MB.',
            'terminal.mimes' => 'The file must be a file of type: jpg, jpeg, png.',
            'terminal.max' => 'The file size must not exceed 20MB.',
            'adoption.mimes' => 'The file must be a file of type: jpg, jpeg, png.',
            'adoption.max' => 'The file size must not exceed 20MB.',
            'from.before_or_equal' => 'The from date must be before or on the to date.',
            'to.after_or_equal' => 'The to date must be after or on the from date.'
        ]);

        $leave->employee_id = $request->employee_id;
        $leave->leave_type = $request->leave_type;
        $leave->from = $request->from;
        $leave->to = $request->to;
        $leave->leave_days = $request->leave_days;
        $leave->details_leave = $request->details_leave;
        $leave->details_leave_sick = $request->details_leave_sick;
        $leave->abroad = $request->abroad;

        // Monetization document upload
        if (!empty($request->file('monetization'))) {
            $ext = $request->file('monetization')->getClientOriginalExtension();
            $file = $request->file('monetization');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('public/leavedocuments/', $filename);
            $leave->monetization = $filename;
        }

        // Terminal document upload
        if (!empty($request->file('terminal'))) {
            $ext = $request->file('terminal')->getClientOriginalExtension();
            $file = $request->file('terminal');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('public/leavedocuments/', $filename);
            $leave->terminal = $filename;
        }

        // Adoption document upload
        if (!empty($request->file('adoption'))) {
            $ext = $request->file('adoption')->getClientOriginalExtension();
            $file = $request->file('adoption');
            $randomStr = date('Ymdhis') . Str::random(20);
            $filename = strtolower($randomStr) . '.' . $ext;
            $file->move('public/leavedocuments/', $filename);
            $leave->adoption = $filename;
        }
        $leave->save();

        return redirect()->back()->with('success', 'Leave successfully added.');
    }



    public function editstatus($id, Request $request)
    {
        // Fetch the specific leave by ID
        $leave = Leave::findOrFail($id);

        // Validate the request
        $request->validate([
            'status' => 'required|in:Pending,Approved,Declined',
        ]);

        // Fetch the related user based on the employee_id in the leave
        $user = User::where('custom_id', $leave->employee_id)->where('is_archive', 1)->first();
        $history = new History();
       
        // Check if the user exists and is archived
        if (!$user) {
            return redirect()->back()->with('error', 'User not found or is not archived.');
        }

        // Define leave types
        $leaveTypes = [
            1  => 'vacation_leave',
            2  => 'vacation_leave',
            3  => 'sick_leave',
            4  => 'maternity_leave',
            5  => 'paternity_leave',
            6  => 'special_previlege_leave',
            7  => 'solo_parent_leave',
            8  => 'study_leave',
            9  => 'vawc_leave',
            10 => 'rehabilitation_leave',
            11 => 'special_leave_benefits_women', // Requires gender check
            12 => 'special_emergency_leave',
            13 => '', // No balance check needed
            14 => '', // No balance check needed
            15 => '', // No balance check needed
        ];

        // Check if the leave type is valid
        if (array_key_exists($leave->leave_type, $leaveTypes)) {
            $leaveField = $leaveTypes[$leave->leave_type];

            // For leave types 13, 14, and 15, skip balance check and update directly
            if (in_array($leave->leave_type, [13, 14, 15])) {
                // No balance check needed, continue with status update
            } else {
                // Additional gender check for special leave
                if ($leave->leave_type == 11 && $user->sex != 'Female') {
                    return redirect()->back()->with('error', 'For Females Only!');
                }

                // Check if the user has sufficient leave balance
                if ($user->{$leaveField} >= $leave->leave_days) {
                    // Subtract leave days from user balance
                    $user->{$leaveField} -= $leave->leave_days;
                    $user->save();

                    // Insert data into history for sick leave or vacation leave
                    if ($leave->leave_type == 1 || $leave->leave_type == 2) {
                        $history->history_id = $user->custom_id;
                       
                        $history->particular = Carbon::parse($leave->from)->format('M. j'). '-' .Carbon::parse($leave->to)->format('j, Y'). '-' . 'Vacation Leave';
                        $history->v_wp = $leave->leave_days;  // Vacation leave
                        $history->v_balance = $user->vacation_leave;
                        $history->date_action = Carbon::now();
                    } elseif ($leave->leave_type == 3) {
                        $history->history_id = $user->custom_id;
                        $history->particular = Carbon::parse($leave->from)->format('M. j'). '-' .Carbon::parse($leave->to)->format('j, Y'). '-' . 'Sick Leave';
                        $history->s_wp = $leave->leave_days;  // Vacation leave
                        $history->s_balance = $user->sick_leave;
                        $history->date_action = Carbon::now();
                    }
                } else {
                    return redirect()->back()->with('error', 'Insufficient balance!');
                }
            }
        } else {
            return redirect()->back()->with('error', 'Invalid leave type!');
        }

        // Update leave status
        $leave->status = $request->status;
        $leave->save();
        $history->save();

        // Redirect based on user type
        $monitor = Auth::user();
        $viewPath = match ($monitor->user_type) {
            0 => '/SuperAdmin/Leave',
            1 => '/Admin/Leave',
            default => '/Employee/Leave',
        };

        return redirect($viewPath)->with('success', 'Leave successfully updated.');
    }




    public function editCredits(Request $request, $id)
    {
        // Validate the form input
        $request->validate([
            'type' => 'required|string',
            'numberInput' => 'required|numeric|min:-100|max:100',
        ]);

        // Retrieve the user by ID
        $user = User::findOrFail($id);
        $history = new History();
        $history->history_id = $user->custom_id;

        // Determine which leave balance to update based on the 'type' input
        switch ($request->input('type')) {
            case 'sick_leave':
                $user->sick_leave += $request->input('numberInput');

               


                if ($request->input('numberInput') > 0) {
                    $history->s_earned = $request->input('numberInput');
                    $history->s_balance = $user->sick_leave;
                } else {
                    $history->s_wp = $request->input('numberInput');
                    $history->s_balance = $user->sick_leave;
                }
                $history->period = Carbon::now();
                break;
            case 'vacation_leave':
                $user->vacation_leave += $request->input('numberInput');
               

                if ($request->input('numberInput') > 0) {
                    $history->v_earned = $request->input('numberInput');
                    $history->v_balance = $user->vacation_leave;
                } else {
                    $history->v_wp = $request->input('numberInput');
                    $history->v_balance = $user->vacation_leave;
                }
                $history->period = Carbon::now();
                break;
            case 'special_previlege_leave':
                $user->special_previlege_leave += $request->input('numberInput');
                break;
            default:
                return redirect()->back()->withErrors(['type' => 'Invalid leave type selected']);
        }

        // Save the updated user record
        $user->save();
        $history->save();

        // Redirect back with a success message
        $monitor = Auth::user();
        $viewPath = match ($monitor->user_type) {
            0 => '/SuperAdmin/Credits',
            1 => '/Admin/Credits',
            default => '/Employee/Dashboard',
        };

        return redirect($viewPath)->with('success', 'Edit credits successfully updated.');
    }



    public function leavegeneratereports(Request $request)
    {
        // Retrieve history based on authenticated user's custom_id
        $history = History::where('history_id', Auth::user()->custom_id) // Adjust if necessary
            ->orderBy('updated_at', 'desc')
            ->get();

            $department = Department::all(); // Accessing department name
            

        // Generate the PDF based on user type
        if (Auth::user()->user_type == 0) {
            // Superadmin view
            $pdf = PDF::loadView('superadmin.leave.mycard', [
                'history' => $history,
                'department' => $department
            ]);
        } elseif (Auth::user()->user_type == 1) {
            // Admin view
            $pdf = PDF::loadView('admin.leave.mycard', [
                'history' => $history,
                'department' => $department
            ]);
        } elseif (Auth::user()->user_type == 2) {
            // Employee view
            $pdf = PDF::loadView('employee.leave.mycard', [
                'history' => $history,
                'department' => $department
            ]);
        }

        // Return the PDF to be viewed in the browser
        return $pdf->inline('mycard_report.pdf');
    }


    public function generateReports(Request $request)
    {
        // Retrieve input values for the date range and employee ID
        $timeframeStart = $request->input('timeframeStart');
        $timeframeEnd = $request->input('timeframeEnd');
        $employeeIds = $request->input('employeeIds');
        $employeetype = $request->input('employeetype');
        $employeestatus = $request->input('employeestatus');

        $statustype = Leavetype::find($employeetype);

        // Initialize the Leave query with the user relationship
        if (Auth::user()->user_type == 0) {
        $leaveData = Leave::query()->with('user','leavetype');
        }
        if (Auth::user()->user_type == 1) {
            $leaveData = Leave::query()->where('employee_id','!=', 1)->with('user','leavetype');
            }
        $dateNow = $this->getInternetTime();
        // Apply employee filter if an employee is selected
        if ($employeeIds) {
            $leaveData->where('employee_id', $employeeIds);
        }
        if ($employeetype) {
            $leaveData->where('leave_type', $employeetype);
        }
        if ($employeestatus) {
            $leaveData->where('status', $employeestatus);
        }

        // Apply date range filter if both start and end dates are provided
        if ($timeframeStart && $timeframeEnd) {
            $leaveData->whereBetween('created_at', [$timeframeStart, $timeframeEnd]);
        }

        // Get the filtered data
        $leaveData = $leaveData->get();

        // Count the records
        $recordCount = $leaveData->count();

        // Generate the PDF with the filtered data, count, and date range

        if (Auth::user()->user_type == 0) {
            $pdf = PDF::loadView('superadmin.leave.generatereports', [
                'statustype' => $statustype,
                'leaveData' => $leaveData,
                'recordCount' => $recordCount,
                'timeframeStart' => $timeframeStart,
                'timeframeEnd' => $timeframeEnd,
                'dateNow' => $dateNow,
                'employeeIds' => $employeeIds,
                'employeestatus' => $employeestatus,
                'employeetype' => $employeetype
            ]);
        }
        if (Auth::user()->user_type == 1) {
            $pdf = PDF::loadView('admin.leave.generatereports', [
                'statustype' => $statustype,
                'leaveData' => $leaveData,
                'recordCount' => $recordCount,
                'timeframeStart' => $timeframeStart,
                'timeframeEnd' => $timeframeEnd,
                'dateNow' => $dateNow,
                'employeeIds' => $employeeIds,
                'employeestatus' => $employeestatus,
                'employeetype' => $employeetype
            ]);
        }

        // Return the PDF to be viewed in the browser
        return $pdf->inline('leave_report.pdf');
    }
}
