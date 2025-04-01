<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\User;


class DepartmentController extends Controller
{

    public function department(Request $request)
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
    

    $search = $request->input('search');

    // Fetch departments that match the search query
    $departments = Department::where('name', 'LIKE', "%{$search}%")
        ->where('deleted', 1)
        ->paginate(10, ['*'], 'page_department');

    // Fetch positions that match the search query
    $position = Position::where('name', 'LIKE', "%{$search}%")
        ->where('deleted', 1)
        ->paginate(10, ['*'], 'page_position');

        $query = Message::getNotify();



        // Fetch positions based on the search query
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


        $getNot['getNotify'] = $query->orderBy('id', 'desc')->take(10)->get();
        $viewPath = Auth::user()->user_type == 0
            ? 'SuperAdmin.Department.Department'
            : (Auth::user()->user_type == 1
                ? 'Admin.Department.Department'
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


    public function departmentarchived(Request $request)
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
    

    $search = $request->input('search');

// Fetch department IDs matching the search query
$departments = Department::where('name', 'LIKE', "%{$search}%")
        ->where('deleted', 2)
        ->paginate(10, ['*'], 'page_department');

// Fetch positions that match the search query and belong to the found departments


// Pass the filtered departments separately if needed



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
            ? 'SuperAdmin.Department.Departmentarchived'
            : (Auth::user()->user_type == 1
                ? 'Admin.Department.Departmentarchived'
                : 'Employee.Message');


        return view($viewPath, [
            'notification' => $notification,
            'getNot' => $getNot,
            'departments' => $departments,
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
            'turnoverRate' => $turnoverRate,
        ]);
    }


    public function getPositions($department_id)
    {
        $positions = Position::where('department_id', $department_id)->get();
        return response()->json($positions);
    }

    public function adddepartment(Request $request)
    {
        $department = new Department;

        $request->validate([
            'name' => 'string|max:50|unique:departments,name',
            'abbreviation' => 'string|max:50|unique:departments,abbreviation',
        ], [
            'name.unique' => 'This name has already been taken.',
            'abbreviation.unique' => 'This abbreviation has already been taken.',
        ]);

        $department->name = $request->name;
        $department->abbreviation = $request->abbreviation;
        $department->save();

        return redirect()->back()->with('success', 'Department successfully added');
    }
    public function updatedepartment($id, Request $request)
    {

        $department = Department::getId($id);
        $request->validate([
            'name' => 'required|string|max:50|unique:departments,name,' . $request->id,
            'abbreviation' => 'required|string|max:50|unique:departments,abbreviation,'. $request->id,
        ],[
            'name.unique' => 'This name has already been taken.',
        ]);
        $department->abbreviation = $request->abbreviation;
        $department->name = $request->name;
        $department->save();

        return redirect()->back()->with('success', 'Department successfully updated');
    }

    public function updateposition($id, Request $request)
    {

        $position = Position::getId($id);
        $request->validate([
            'name' => 'required|string|max:50|unique:positions,name,' . $request->id,
            'abbreviation' => 'required|string|max:50|unique:positions,abbreviation,'. $request->id,
        ],[
            'name.unique' => 'This name has already been taken.',
        ]);

        $position->name = $request->name;
        $position->abbreviation = $request->abbreviation;
        $position->save();

        return redirect()->back()->with('success', 'Department successfully updated');
    }


    public function addposition(Request $request)
    {

        $position = new Position;

        $request->validate([
            'name' => 'required|string|max:50',
            'abbreviation' => 'required|string|max:50',
            'department_id' => 'required|integer|exists:departments,id',
        ], [
            'name.required' => 'The name field is required.',
            'name.unique' => 'This name has already been taken.',
            'department_id.required' => 'The department field is required.',
            'department_id.exists' => 'The selected department is invalid.',
        ]);


        $position->abbreviation = $request->abbreviation;
        $position->name = $request->name;
        $position->department_id = $request->department_id;

        $position->save();

        return redirect()->back()->with('success', 'Position successfully added');
    }

    public function deleted($id)
    {
        $user = Department::getId($id);
        $user->deleted = 2;
        $user->save();

        return redirect()->back()->with('success', 'Department successfully DELETED');
    }
    public function deletedrestored($id)
    {
        $user = Department::getId($id);
        $user->deleted = 1;
        $user->save();

        return redirect()->back()->with('success', 'Department successfully RESTORED');
    }

    public function deletedposition($id)
{
    // Find the position by ID
    $position = Position::findOrFail($id);

    // Permanently delete the position from the database
    $position->forceDelete();

    return redirect()->back()->with('success', 'Position successfully deleted');
}

}
