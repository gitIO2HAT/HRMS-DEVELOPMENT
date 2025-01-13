<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\FingerprintController;
use App\Models\Attendance;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [LoginDashboardController::class, 'login'])->name('login');
Route::post('login', [LoginDashboardController::class, 'AuthLogin']);
Route::get('/ForgetPassword', [LoginDashboardController::class, 'forgetpassword']);
Route::post('/ForgetPassword/Reset', [LoginDashboardController::class, 'sendResetLinkEmail']);
Route::get('/logout', [LoginDashboardController::class, 'logoutButton'])->name('logoutButton');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset']);

Route::group(['middleware' => 'superadmin'], function () {

    Route::get('/SuperAdmin/Dashboard', [DashboardController::class, 'dashboard']);

    Route::get('/SuperAdmin/Employee', [EmployeeController::class, 'employee']);
    Route::get('/SuperAdmin/Employee/ArchiveEmployee', [EmployeeController::class, 'archiveemployee']);
    Route::get('/SuperAdmin/Employee/AddEmployee', [EmployeeController::class, 'addemployee']);
    Route::post('/SuperAdmin/Employee/AddEmployee', [EmployeeController::class, 'insertemployee']);
    Route::get('/SuperAdmin/Employee/EditEmployee/{id}', [EmployeeController::class, 'editemployee']);
    Route::post('/SuperAdmin/Employee/EditEmployee/{id}', [EmployeeController::class, 'updateemployee']);
    Route::get('/SuperAdmin/Employee/PreviewEmployee/{id}', [EmployeeController::class, 'previewemployee']);
    Route::get('/SuperAdmin/Employee/Archive/{id}', [EmployeeController::class, 'archive']);
    Route::get('/SuperAdmin/Employee/Restore/{id}', [EmployeeController::class, 'restore']);

    Route::get('/SuperAdmin/Leave', [LeaveController::class, 'leave']);
    Route::get('/SuperAdmin/MyLeave', [LeaveController::class, 'myleave']);
    Route::get('/SuperAdmin/Credits', [LeaveController::class, 'credits']);
    Route::patch('/SuperAdmin/Credits/EditCredits/{id}', [LeaveController::class, 'editcredits']);
    Route::post('/SuperAdmin/Leave/AddLeave', [LeaveController::class, 'addleave']);
    Route::patch('/SuperAdmin/Leave/AddLeave/EditStatus/{id}', [LeaveController::class, 'editstatus']);
    Route::get('/SuperAdmin/Leave/MyLeaveCreditCard', [LeaveController::class, 'leavegeneratereports']);
    Route::post('/SuperAdmin/Leave/GenerateReports', [LeaveController::class, 'generateReports']);

    Route::get('/SuperAdmin/Announcement', [AnnouncementController::class, 'announcement']);
    Route::post('/SuperAdmin/Announcement', [AnnouncementController::class, 'save_task']);
    Route::get('/SuperAdmin/Read/{id}', [AnnouncementController::class, 'read']);

    Route::get('/SuperAdmin/Department', [DepartmentController::class, 'department']);
    Route::post('/SuperAdmin/Department/UpdateDepartment/{id}', [DepartmentController::class, 'updatedepartment']);
    Route::get('/SuperAdmin/Department/DepartmentArchived', [DepartmentController::class, 'departmentarchived']);
    Route::get('/SuperAdmin/Department/Deleted/{id}', [DepartmentController::class, 'deleted']);
    Route::get('/SuperAdmin/Department/DeletedRestored/{id}', [DepartmentController::class, 'deletedrestored']);
    Route::get('/SuperAdmin/Department/DeletedPosition/{id}', [DepartmentController::class, 'deletedposition']);
    Route::get('/SuperAdmin/Department/DeletedPositionRestored/{id}', [DepartmentController::class, 'deletedpositionrestored']);
    Route::post('/SuperAdmin/Department/AddDepartment', [DepartmentController::class, 'adddepartment']);
    Route::post('/SuperAdmin/Department/UpdatePosition/{id}', [DepartmentController::class, 'updateposition']);
    Route::post('/SuperAdmin/Department/AddPosition', [DepartmentController::class, 'addposition']);
    Route::get('/SuperAdmin/positionsSuper/{department_id}', [DepartmentController::class, 'getPositions']);

    Route::get('/SuperAdmin/Attendance', [AttendanceController::class, 'attendance']);
    Route::get('/SuperAdmin/MyAttendance', [AttendanceController::class, 'myattendance']);
    Route::post('/SuperAdmin/ClockIn', [AttendanceController::class, 'clockIn']);
    Route::post('/SuperAdmin/ClockOut', [AttendanceController::class, 'clockOut']);
    Route::get('/current-time-superadmin', [AttendanceController::class, 'currentTime'])->name('current-time-superadmin');
    Route::post('/SuperAdmin/Attendance/GenerateReports', [AttendanceController::class, 'generatereports']);
    Route::get('/SuperAdmin/Attendance/DailyTimeRecord', [AttendanceController::class, 'dtrreports']);

    Route::get('/SuperAdmin/MyAccount', [MyAccountController::class, 'myaccount']);
    Route::post('/SuperAdmin/MyAccount/Update', [MyAccountController::class, 'updatemyaccount']);

    Route::get('/SuperAdmin/Fingerprint', [FingerprintController::class, 'fingerprint']);
    Route::get('/SuperAdmin/Fingerprint/Active/{id}', [FingerprintController::class, 'Active']);
    Route::get('/SuperAdmin/Fingerprint/NotActive/{id}', [FingerprintController::class, 'NotActive']);
});

Route::group(['middleware' => 'admin'], function () {
    Route::get('/Admin/Dashboard', [DashboardController::class, 'dashboard']);

    Route::get('/Admin/Employee', [EmployeeController::class, 'employee']);
    Route::get('/Admin/Employee/ArchiveEmployee', [EmployeeController::class, 'archiveemployee']);
    Route::get('/Admin/Employee/AddEmployee', [EmployeeController::class, 'addemployee']);
    Route::post('/Admin/Employee/AddEmployee', [EmployeeController::class, 'insertemployee']);
    Route::get('/Admin/Employee/EditEmployee/{id}', [EmployeeController::class, 'editemployee']);
    Route::post('/Admin/Employee/EditEmployee/{id}', [EmployeeController::class, 'updateemployee']);
    Route::get('/Admin/Employee/PreviewEmployee/{id}', [EmployeeController::class, 'previewemployee']);
    Route::get('/Admin/Employee/Archive/{id}', [EmployeeController::class, 'archive']);
    Route::get('/Admin/Employee/Restore/{id}', [EmployeeController::class, 'restore']);


    Route::get('/Admin/Leave', [LeaveController::class, 'leave']);
    Route::get('/Admin/MyLeave', [LeaveController::class, 'myleave']);
    Route::get('/Admin/Credits', [LeaveController::class, 'credits']);
    Route::patch('/Admin/Credits/EditCredits/{id}', [LeaveController::class, 'editcredits']);
    Route::post('/Admin/Leave/AddLeave', [LeaveController::class, 'addleave']);
    Route::patch('/Admin/Leave/AddLeave/EditStatus/{id}', [LeaveController::class, 'editstatus']);
    Route::get('/Admin/Leave/MyLeaveCreditCard', [LeaveController::class, 'leavegeneratereports']);
    Route::post('/Admin/Leave/GenerateReports', [LeaveController::class, 'generateReports']);

    Route::get('/Admin/Announcement', [AnnouncementController::class, 'announcement']);
    Route::post('Admin/Announcement', [AnnouncementController::class, 'save_task']);
    Route::get('/Admin/Read/{id}', [AnnouncementController::class, 'read']);
 
    Route::get('/Admin/Attendance', [AttendanceController::class, 'attendance']);
    Route::get('/Admin/MyAttendance', [AttendanceController::class, 'myattendance']);
    Route::post('/Admin/ClockIn', [AttendanceController::class, 'clockIn']);
    Route::post('/Admin/ClockOut', [AttendanceController::class, 'clockOut']);
    Route::get('/current-time-admin', [AttendanceController::class, 'currentTime'])->name('current-time-admin');
    Route::post('/Admin/Attendance/GenerateReports', [AttendanceController::class, 'generatereports']);
    Route::get('/Admin/Attendance/DailyTimeRecord', [AttendanceController::class, 'dtrreports']);


    Route::get('/Admin/Department', [DepartmentController::class, 'department']);
    Route::post('/Admin/Department/UpdateDepartment/{id}', [DepartmentController::class, 'updatedepartment']);
    Route::get('/Admin/Department/DepartmentArchived', [DepartmentController::class, 'departmentarchived']);
    Route::get('/Admin/Department/Deleted/{id}', [DepartmentController::class, 'deleted']);
    Route::get('/Admin/Department/DeletedRestored/{id}', [DepartmentController::class, 'deletedrestored']);
    Route::get('/Admin/Department/DeletedPosition/{id}', [DepartmentController::class, 'deletedposition']);
    Route::get('/Admin/Department/DeletedPositionRestored/{id}', [DepartmentController::class, 'deletedpositionrestored']);
    Route::post('/Admin/Department/AddDepartment', [DepartmentController::class, 'adddepartment']);
    Route::post('/Admin/Department/UpdatePosition/{id}', [DepartmentController::class, 'updateposition']);
    Route::post('/Admin/Department/AddPosition', [DepartmentController::class, 'addposition']);
    Route::get('/Admin/positionsAdmin/{department_id}', [DepartmentController::class, 'getPositions']);

    Route::get('/Admin/MyAccount', [MyAccountController::class, 'myaccount']);
    Route::post('/Admin/MyAccount/Update', [MyAccountController::class, 'updatemyaccount']);

    Route::get('/Admin/Fingerprint', [FingerprintController::class, 'fingerprint']);
    Route::get('/Admin/Fingerprint/Active/{id}', [FingerprintController::class, 'Active']);
    Route::get('/Admin/Fingerprint/NotActive/{id}', [FingerprintController::class, 'NotActive']);
});



Route::group(['middleware' => 'employee'], function () {

    Route::get('/Employee/Dashboard', [DashboardController::class, 'dashboard']);

    Route::get('/Employee/MyLeave', [LeaveController::class, 'myleave']);
    Route::get('/Employee/Leave/MyLeaveCreditCard', [LeaveController::class, 'leavegeneratereports']);


    Route::get('/Employee/MyAccount', [MyAccountController::class, 'myaccount']);
    Route::post('/Employee/MyAccount/Update', [MyAccountController::class, 'updatemyaccount']);

    Route::get('/Employee/Read/{id}', [AnnouncementController::class, 'read']);
    Route::get('/Employee/Attendance', [AttendanceController::class, 'attendance']);
    Route::post('/Employee/ClockIn', [AttendanceController::class, 'clockIn']);
    Route::post('/Employee/ClockOut', [AttendanceController::class, 'clockOut']);
    Route::get('/current-time-employee', [AttendanceController::class, 'currentTime'])->name('current-time-employee');
    Route::get('/Employee/Attendance/DailyTimeRecord', [AttendanceController::class, 'dtrreports']);
});
