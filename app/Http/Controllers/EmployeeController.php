<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\Department;
use App\Models\Position;

class EmployeeController extends Controller
{
    public function employee(Request $request)
    {
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


        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();

        $query = User::getEmployee();
        $depart = Department::all();
        $pos = Position::all();
        $search = $request->input('search');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(name, ' ', lastname) LIKE ?", ["%$search%"])
                    ->orWhere('custom_id', 'LIKE', "%$search%")
                    ->orWhere('department', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('end_of_contract', 'LIKE', "%$search%")
                    ->orWhere('position', 'LIKE', "%$search%")
                    ->orWhere('daily_rate', 'LIKE', "%$search%");
            });
        }

        $data['getEmployee'] = $query
            ->orderBy('id', 'desc')
            ->paginate(10);


        if (Auth::user()->user_type === 0) {
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

        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Employee.Employee'
            : (Auth::user()->user_type == 1
                ? 'Admin.Employee.Employee'
                : 'Employee.Dashboard');

        return view($viewPath, $data, [
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
            'depart' => $depart,
            'pos' => $pos,
            'turnoverRate' => $turnoverRate
        ]);
    }

    public function addemployee(Request $request)
    {
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

        // Fetch departments that match the search query and are not marked as deleted
        $departments = Department::where('name', 'LIKE', "%{$search}%")
            ->where('deleted', 1)
            ->paginate(10);

        // Fetch positions that belong to the found departments and are not marked as deleted
        $departmentIds = $departments->pluck('id');
        $position = Position::whereIn('department_id', $departmentIds)
            ->where('deleted', 1)
            ->where('name', 'LIKE', "%{$search}%")
            ->paginate(10);


        if (Auth::user()->user_type === 0) {
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
            $departmentsedits = $departmentCounts->pluck('department');
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
            $departmentsedits = $departmentCounts->pluck('department');
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


        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();
        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Employee.Addemployee'
            : (Auth::user()->user_type == 1
                ? 'Admin.Employee.Addemployee'
                : 'Employee.Dashboard');


        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
            'departments' => $departments,
            'position' => $position,
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
            'departmentsedits' => $departmentsedits,
            'counts' => $counts,
            'employeesStayed' => $employeesStayed,
            'totalEmployeesAtStart' => $totalEmployeesAtStart,
            'retentionRate' => $retentionRate,
            'averageEmployees' => $averageEmployees,
            'employeesLeft' => $employeesLeft,
            'turnoverRate' => $turnoverRate
        ]);
    }

    public function insertemployee(Request $request)
    {
        $user = new User;

        // Create a new user instance
        $request->validate([
            'name' => 'required|string|max:30',
            'middlename' => 'nullable|string|max:30',
            'lastname' => 'required|string|max:30',
            'suffix' => 'nullable|in:Jr.,Sr.,I,II,III,N/A',
            'sex' => 'required|in:Male,Female,Other',
            'age' => 'required|integer|min:18',
            'birth_date' => 'required|date',
            'phonenumber' => 'required|string|max:20',
            'department' => 'required|integer', // Validate as integer if using IDs
            'position' => 'required|integer',   // Validate as integer if using IDs
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:4',
            'end_of_contract' => 'required|date',
            'user_type' => 'required|integer',
            'daily_rate' => 'required|numeric|min:0',
            'contract' => 'required|in:1,2,3,4,5',
        ], [
            'name.required' => 'The name field is required.',
            'middlename.required' => 'The middlename field is required.',
            'lastname.required' => 'The lastname field is required.',
            'sex.required' => 'The sex field is required.',
            'sex.in' => 'Invalid value for sex.',
            'age.required' => 'The age field is required.',
            'age.integer' => 'The age must be an integer.',
            'age.min' => 'The age must be at least 18.',
            'birth_date.required' => 'The birth date field is required.',
            'birth_date.date' => 'Invalid date format for birth date.',
            'phonenumber.required' => 'The phonenumber field is required.',
            'department.required' => 'The department field is required.',
            'department.integer' => 'Invalid value for department.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Invalid email format.',
            'email.unique' => 'This email has already been taken.',
            'password.required' => 'The password field is required.',
            'password.string' => 'Invalid password format.',
            'password.min' => 'The password must be at least 8 characters.',
            'user_type.required' => 'The user type field is required.',
            'user_type.integer' => 'Invalid value for user type.',
            'daily_rate.required' => 'The daily rate field is required.',
            'contract.required' => 'The contract field is required.',
            'daily_rate.numeric' => 'Invalid value for daily rate.',
            'daily_rate.min' => 'The daily rate must be at least 0.',
        ]);

        // Assign values to user properties
        $user->name = trim($request->name);
        $user->middlename = trim($request->middlename);
        $user->lastname = trim($request->lastname);
        $user->suffix = $request->suffix;
        $user->contract = $request->contract;
        $user->sex = $request->sex;
        $user->age = $request->age;
        $user->date_of_assumption =  Carbon::now();
        $user->birth_date = $request->input('birth_date') ? trim($request->input('birth_date')) : null;
        $user->phonenumber = trim($request->phonenumber);



        $user->department = $request->department;
        $user->position = $request->position;

        // Find the position by ID and assign the name


        $user->email = trim($request->email);
        $user->password = Hash::make($request->password);
        $user->end_of_contract = $request->input('end_of_contract') ? trim($request->input('end_of_contract')) : null;
        $user->user_type = $request->user_type;
        $user->daily_rate = $request->daily_rate;

        // Generate custom ID
        $currentYear = Carbon::now()->format('Y');
        $latestUserId = User::latest('id')->first(); // Get the latest user ID
        $nextUserId = ($latestUserId) ? $latestUserId->id + 1 : 1; // Increment the latest user ID
        $customId = $currentYear . '-' . sprintf('%05d', $nextUserId); // Format the custom ID

        // Assign the custom ID to the user
        $user->custom_id = $customId;

        // Save the user to the database
        $user->save();

        $user = Auth::user();
        $viewPath = $user->user_type == 0
            ? '/SuperAdmin/Employee'
            : ($user->user_type == 1
                ? '/Admin/Employee'
                : '/Employee/Employee');

        return redirect($viewPath)->with('success', 'User successfully added');
    }


    public function editemployee($id)
    {
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


        $depart = Department::all();
        $pos = Position::all();

        if (Auth::user()->user_type === 0) {
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
            $departmentsedits = $departmentCounts->pluck('department');
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
            $departmentsedits = $departmentCounts->pluck('department');
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


        $departments = Department::all();
        $positions = Position::all();
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();
        $data['getId'] = User::getId($id);

        if (!empty($data['getId'])) {
            $viewPath = Auth::user()->user_type == 0
                ? 'SuperAdmin.Employee.Editemployee'
                : (Auth::user()->user_type == 1
                    ? 'Admin.Employee.Editemployee'
                    : 'Employee.Dashboard');


            return view($viewPath, $data, [
                'notification' => $notification,
                'departments' => $departments,
                'positions' => $positions,
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
                'departmentsedits' => $departmentsedits,
                'counts' => $counts,
                'employeesStayed' => $employeesStayed,
                'totalEmployeesAtStart' => $totalEmployeesAtStart,
                'retentionRate' => $retentionRate,
                'averageEmployees' => $averageEmployees,
                'employeesLeft' => $employeesLeft,
                'depart' => $depart,
                'pos' => $pos,
                'turnoverRate' => $turnoverRate
            ]);
        } else {
            abort(404);
        }
    }

    public function updateemployee($id, Request $request)
    {
        $user = User::find($id); // Assuming you're finding the user by ID

        // Validate the input fields
        $request->validate([
            'department' => 'nullable|integer', // Validate as integer if using IDs
            'position' => 'nullable|integer',
            'date_of_assumption' => 'nullable|date',
            'contract' => 'nullable|in:1,2,3,4,5',  // Validate as integer if using IDs
            'end_of_contract' => 'nullable|date',
        ], [
            'department.integer' => 'Invalid value for department.',
            'daily_rate.numeric' => 'Invalid value for daily rate.',
            'daily_rate.min' => 'The daily rate must be at least 0.',
        ]);

        // Check if the department field is provided in the request

        $user->department = $request->department;
        $user->position = $request->position;
        $user->date_of_assumption = $request->input('date_of_assumption') ? trim($request->input('date_of_assumption')) : null;

        // Check if the position field is provided in the request
        $user->contract = $request->contract;
        $user->end_of_contract = $request->input('end_of_contract') ? trim($request->input('end_of_contract')) : null;
        $user->daily_rate = $request->daily_rate;

        $user->save();

        $user = Auth::user();
        $viewPath = $user->user_type == 0
        ? '/SuperAdmin/Employee'
        : ($user->user_type == 1
            ? '/Admin/Employee'
            : '/Employee/Employee');
        return redirect($viewPath)->with('success', 'User successfully Edit');
        
    }


    public function previewemployee($id)
    {
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

        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();
        $data['getId'] = User::getId($id);
        $depart = Department::all();
        $pos = Position::all();

        if (Auth::user()->user_type === 0) {
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

        if (!empty($data['getId'])) {
            $viewPath = Auth::user()->user_type == 0
                ? 'SuperAdmin.Employee.Previewemployee'
                : (Auth::user()->user_type == 1
                    ? 'Admin.Employee.Previewemployee'
                    : 'Employee.Dashboard');


            return view($viewPath, $data, [
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
                'pos'=> $pos,
                'depart' => $depart,
                'turnoverRate' => $turnoverRate
            ]);
        } else {
            abort(404);
        }
    }
    public function archive($id)
    {
        $user = User::getId($id);
        $user->is_archive = 2;
        $user->date_archive = now()->format('Y-m-d H:i:s');
        $user->save();

        return redirect()->back()->with('success', 'Employee successfully archived');
    }
    public function restore($id)
    {
        $user = User::getId($id);
        $user->is_archive = 1;
        $user->date_archive = null;
        $user->save();

        return redirect()->back()->with('success', 'Employee successfully restore');
    }

    public function archiveemployee(Request $request)
    {
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


        $depart = Department::all();
        $pos = Position::all();
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();
        $query = User::getArchiveEmployee();

        $search = $request->input('search');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT(name, ' ', lastname) LIKE ?", ["%$search%"])
                    ->orWhere('custom_id', 'LIKE', "%$search%")
                    ->orWhere('department', 'LIKE', "%$search%")
                    ->orWhere('email', 'LIKE', "%$search%")
                    ->orWhere('end_of_contract', 'LIKE', "%$search%")
                    ->orWhere('position', 'LIKE', "%$search%")
                    ->orWhere('daily_rate', 'LIKE', "%$search%");
            });
        }

        $data['getEmployee'] = $query->orderBy('id', 'desc')->paginate(10);

        if (Auth::user()->user_type === 0) {
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

        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Employee.Archiveemployee'
            : (Auth::user()->user_type == 1
                ? 'Admin.Employee.Archiveemployee'
                : 'Employee.Dashboard');


        return view($viewPath, $data, [
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
            'depart' => $depart,
            'pos' => $pos
        ]);
    }
}
