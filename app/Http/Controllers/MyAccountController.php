<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Department;
use App\Models\Position;
use App\Models\Message;
class MyAccountController extends Controller
{
    public function myaccount()
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
    

$depart = Department::all();
$pos = Position::all();
        $query = Message::getNotify();
        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();

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
            ? 'SuperAdmin.Myaccount.Myaccount'
            : (Auth::user()->user_type == 1
                ? 'Admin.Myaccount.Myaccount'
                : 'Employee.Myaccount.Myaccount');


        return view($viewPath,[
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
    public function updatemyaccount(Request $request)
    {
        try {
            $id = Auth::user()->id;

            $messages = [
                'pds_file.mimes' => 'The file must be a file of type: pdf, xlsx, xls.',
                'pds_file.max' => 'The file size must not exceed 20MB.',
                'name.required' => 'The name field is required.',
                'lastname.required' => 'The lastname field is required.',
                'sex.required' => 'The sex field is required.',
                'sex.in' => 'Invalid value for sex.',
                'age.required' => 'The age field is required.',
                'age.integer' => 'The age must be an integer.',
                'age.min' => 'The age must be at least 18.',
                'civil_status.required' => 'The civil status field is required.',
                'civil_status.in' => 'Invalid value for civil status.',
                'birth_date.required' => 'The birth date field is required.',
                'birth_date.date' => 'Invalid date format for birth date.',
                'phonenumber.required' => 'The phonenumber field is required.',
                'fulladdress.required' => 'The full address field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Invalid email format.',
                'email.unique' => 'This email has already been taken.',
                'password.required' => 'The password field is required.',
                'password.string' => 'Invalid password format.',
                'password.min' => 'The password must be at least 4 characters.',
                'emergency_fullname.required' => 'The emergency contact full name field is required.',
                'emergency_fulladdress.required' => 'The emergency contact full address field is required.',
                'emergency_phonenumber.required' => 'The emergency contact phone number field is required.',
                'emergency_relationship.required' => 'The emergency contact relationship field is required.',
            ];

            $request->validate([
                'pds_file' => 'required|mimes:pdf,xlsx,xls|max:20480', // 20MB file size limit
                'name' => 'required|string|max:30',
                'middlename' => 'nullable|string|max:30',
                'lastname' => 'required|string|max:30',
                'suffix' => 'nullable|in:Jr.,Sr.,I,II,III,N/A',
                'sex' => 'required|in:Male,Female,Other',
                'age' => 'required|integer|min:18',
                'civil_status' => 'required|in:Single,Married,Widowed',
                'birth_date' => 'required|date',
                'phonenumber' => 'required|string|max:20',
                'fulladdress' => 'required|string|max:150',
                'email' => 'required|email|unique:users,email,'. $id,
                'password' => 'nullable|string|min:4',
                'emergency_fullname' => 'required|string|max:90',
                'emergency_fulladdress' => 'required|string|max:150',
                'emergency_phonenumber' => 'required|string|max:20',
                'emergency_relationship' => 'required|string|max:50',
            ], $messages);

            // Log that validation was successful
            Log::info('Validation passed for user ID: ' . $id);

            $user = User::getId($id);

            $user->name = trim($request->name);
            $user->middlename = trim($request->middlename);
            $user->lastname = trim($request->lastname);
            $user->suffix = $request->suffix;
            $user->sex = $request->sex;
            $user->age = $request->age;
            $user->birth_date = $request->input('birth_date') ? trim($request->input('birth_date')) : null;
            $user->phonenumber = trim($request->phonenumber);
            $user->civil_status = $request->civil_status;
            $user->fulladdress = $request->fulladdress;
            $user->email = trim($request->email);
            $user->emergency_fullname = $request->emergency_fullname;
            $user->emergency_fulladdress = $request->emergency_fulladdress;
            $user->emergency_phonenumber = trim($request->emergency_phonenumber);
            $user->emergency_relationship = $request->emergency_relationship;

            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }

            if (!empty($request->file('profile_pic'))) {
                $ext = $request->file('profile_pic')->getClientOriginalExtension();
                $file = $request->file('profile_pic');
                $randomStr = date('Ymdhis') . Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move('public/accountprofile/', $filename);
                $user->profile_pic = $filename;
            }
            if (!empty($request->file('pds_file'))) {
                $ext = $request->file('pds_file')->getClientOriginalExtension();
                $file = $request->file('pds_file');
                $randomStr = date('Ymdhis') . Str::random(20);
                $filename = strtolower($randomStr) . '.' . $ext;
                $file->move('public/employeepdsfile/', $filename);
                $user->pds_file = $filename;
            }

            $user->save();

            // Log successful save
            Log::info('User profile updated successfully for user ID: ' . $id);

            $user = Auth::user();
            $viewPath = $user->user_type == 0
            ? '/SuperAdmin/Dashboard'
            : ($user->user_type == 1
                ? '/Admin/Dashboard'
                : '/Employee/Dashboard');
            return redirect($viewPath)->with('success', ' successfully update profile');

        } catch (\Exception $e) {
            // Log the error message
            Log::error('Error updating profile for user ID: ' . $id . ' - ' . $e->getMessage());

            return redirect()->back()->with('error', 'An error occurred while updating your profile.' . ' - ' . $e->getMessage());
        }
}
}
