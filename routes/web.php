<?php

use App\Events\Leave;
use App\Events\Test;
use App\Http\Controllers\ActivityTrackerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\DashboardController as HRDashboardController;
use App\Http\Controllers\HR\EmployeeAttributes;
use App\Http\Controllers\HR\LeaveController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\EmployeeDetailController;
use App\Http\Controllers\HR\EmployeeLeaveRequestController;
use App\Http\Controllers\HR\HolidayController;
use App\Http\Controllers\User\CheckInController;
use App\Http\Controllers\User\CompanyPolicyController;
use App\Http\Controllers\User\EmployeeAttendanceController;
use App\Http\Controllers\User\LeaveRequestController;
use App\Http\Controllers\User\ModuleController;
use App\Http\Controllers\User\TeamMemberLeaveRequestController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\HR\ViewTimeTrackerController;
use App\Http\Controllers\User\TimeTrackerController;
use App\Http\Controllers\HR\HrCompanyPolicyController;
use App\Http\Controllers\HR\AnnouncementController;
use App\Http\Controllers\HR\FileUploadController;
use App\Http\Controllers\User\EmployeeFileViewController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('send-mail', [Controller::class, 'sendMail']);

//Login Routes
Route::get('user_login', [AuthController::class, 'user_login_view'])->name('user_login_view');
Route::get('hr_login', [AuthController::class, 'hr_login_view'])->name('hr_login_view');
Route::get('super_admin_login', [AuthController::class, 'super_admin_login_view'])->name('super_admin_login_view');
Route::post('user_login', [AuthController::class, 'user_login_post'])->name('user_login_post');
Route::post('hr_login', [AuthController::class, 'hr_login_post'])->name('hr_login_post');
Route::post('super_admin_login', [AuthController::class, 'super_admin_login_post'])->name('super_admin_login_post');
Route::get('user_register/{token}', [AuthController::class, 'user_register'])->name('user_register');
Route::post('user_register_post', [AuthController::class, 'user_register_post'])->name('user_register_post');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('update_application_time', [AuthController::class, 'update_application_time'])->name('update_application_time');




Route::group(['middleware' => ['superadmin'], 'prefix' => '/'], function () {
    Route::get('super_admin_dashboard', [DashboardController::class, 'super_admin_dashboard'])->name('super_admin_dashboard');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('activity-tracker/update', [ActivityTrackerController::class, 'updateActivity'])->name('activity-tracker.update');
    Route::get('activity_tracker/{date?}', [ActivityTrackerController::class, 'index'])->name('activity_tracker.index.with');
    Route::get('activity_tracker/show/{id}/{date?}', [ActivityTrackerController::class, 'show'])->name('activity_tracker.show.with');
    Route::resource('activity_tracker', ActivityTrackerController::class);
});


Route::group(['middleware' => ['hr'], 'prefix' => '/'], function () {
    Route::get('hr_dashboard', [HRDashboardController::class, 'hr_dashboard'])->name('hr_dashboard');
    Route::post('reponse_employee_leave_application', [EmployeeLeaveRequestController::class, 'reponse_employee_leave_application'])->name('reponse_employee_leave_application');
    Route::post('add_department', [EmployeeAttributes::class, 'addDepartment'])->name('add_department');
    Route::post('add_source_of_hire', [EmployeeAttributes::class, 'addSourceofHire'])->name('add_source_of_hire');
    Route::post('add_location', [EmployeeAttributes::class, 'addLocation'])->name('add_location');
    Route::post('add_employee_type', [EmployeeAttributes::class, 'addEmployeeType'])->name('add_employee_type');
    Route::post('add_title', [EmployeeAttributes::class, 'addTitle'])->name('add_title');
    Route::resource('employee', EmployeeController::class);
    Route::get('employees/inactive', [EmployeeController::class, 'indexinactive'])->name('employee.inactive');
    Route::resource('holiday', HolidayController::class);
    Route::resource('company-policy', HrCompanyPolicyController::class);
    Route::resource('announcement', AnnouncementController::class);
    Route::get('employee_attendance', [AttendanceController::class, 'employee_attendance'])->name('employee_attendance');
    Route::get('employee_detail/{id}/{start_date}/{end_date}', [EmployeeDetailController::class, 'employee_detail'])->name('employee_detail');
    Route::get('employee-attendance-pdf/{user_id}/{start_date}/{end_date}', [PDFController::class, 'generateAttendanceData'])->name('employee-attendance-pdf');
    Route::get('employee-time-tracker-pdf/{user_id}/{start_date}/{end_date}', [PDFController::class, 'generateTimeTrackerData'])->name('employee-time-tracker-pdf');
    Route::get('employee_leave_request', [EmployeeLeaveRequestController::class, 'employee_leave_request'])->name('employee_leave_request');
    Route::post('update_log_time', [EmployeeDetailController::class, 'update_log_time'])->name('update_log_time');
    Route::get('view_employee_time_tracker/{id}/{start_date?}/{end_date?}', [ViewTimeTrackerController::class, 'view_employee_time_tracker'])->name('view_employee_time_tracker');
    Route::get('add_hr_time_tracker_info', [TimeTrackerController::class, 'add_time_tracker_info'])->name('add_hr_time_tracker_info');
    Route::post('add_comment', [AttendanceController::class, 'add_comment'])->name('add_comment');
    Route::post('update_comment', [AttendanceController::class, 'update_comment'])->name('update_comment');
    Route::get('hr_checkin', [CheckInController::class, 'user_checkin'])->name('hr_checkin');
    Route::get('hr_add_time_tracker_info', [ViewTimeTrackerController::class, 'hr_add_time_tracker_info'])->name('hr_add_time_tracker_info');
    Route::post('create_hr_time_tracker_info', [ViewTimeTrackerController::class, 'create_hr_time_tracker_info'])->name('create_hr_time_tracker_info');
    Route::get('edit_hr_time_tracker_info/{id}', [ViewTimeTrackerController::class, 'edit_hr_time_tracker_info'])->name('edit_hr_time_tracker_info');
    Route::post('update_hr_time_tracker_info', [ViewTimeTrackerController::class, 'update_hr_time_tracker_info'])->name('update_hr_time_tracker_info');
    Route::post('hr_add_project_name', [ViewTimeTrackerController::class, 'add_project_name'])->name('hr_add_project_name');
    Route::post('hr_add_job_name', [ViewTimeTrackerController::class, 'add_job_name'])->name('hr_add_job_name');
    Route::get('attendance_report', [AttendanceController::class, 'attendance_report'])->name('attendance_report');
    Route::get('attendance_report_pdf/{user_id}/{month}/{year}', [PDFController::class, 'generateAttendanceReport'])->name('attendance_report_pdf');
    Route::get('checkins_report/{user_id}/{month}/{year}', [PDFController::class, 'generateCheckinsReport'])->name('checkins_report_pdf');
    Route::post('change-password', [HRDashboardController::class, 'changePassword'])->name('change-password');
    Route::get('mark-as-alll-read', [HRDashboardController::class, 'markasallread'])->name('mark-as-alll-read-hr');
    Route::get('hr_view_leave_request', [LeaveController::class, 'view_leave_request'])->name('hr_view_leave_request');
    Route::get('hr_view_leave_request', [LeaveController::class, 'view_leave_request'])->name('hr_view_leave_request');
    Route::post('hr_add_leave_request', [LeaveController::class, 'add_leave_request'])->name('hr_add_leave_request');
    Route::put('hr_update_leave_request/{leave}', [LeaveController::class, 'update_leave_request'])->name('hr_update_leave_request');

    Route::get('hr_delete_time_tracker_info/{id} ', [ViewTimeTrackerController::class, 'hr_DeleteTimeTracker'])->name('hr_delete_time_tracker');

    Route::delete('delete-leave-request/{id}', [EmployeeLeaveRequestController::class, 'delete'])->name('delete-leave-request');

    Route::get('upload-file', [FileUploadController::class, 'create'])->name('hr.upload.form');

    Route::post('upload-file', [FileUploadController::class, 'store'])->name('hr.upload.file');


    Route::delete('upload-file/{id}', [FileUploadController::class, 'destroy'])->name('hr.upload.file.delete');
});



Route::group(['middleware' => ['user'], 'prefix' => '/'], function () {
    Route::get('user_dashboard', [UserDashboardController::class, 'user_dashboard'])->name('user_dashboard');
    Route::post('add_leave_request', [LeaveRequestController::class, 'add_leave_request'])->name('add_leave_request');
    Route::post('update_leave_request/{leave}', [LeaveRequestController::class, 'update_leave_request'])->name('update_leave_request');
    Route::get('view_leave_request', [LeaveRequestController::class, 'view_leave_request'])->name('view_leave_request');
    Route::get('view_team_member_leave_request', [TeamMemberLeaveRequestController::class, 'view_team_member_leave_request'])->name('view_team_member_leave_request');
    Route::get('user_checkin', [CheckInController::class, 'user_checkin'])->name('user_checkin');
    Route::get('allholidays', [ModuleController::class, 'allholidays'])->name('all-holidays');
    Route::get('attendance/{start_date}/{end_date}', [EmployeeAttendanceController::class, 'attendance'])->name('attendance');
    Route::get('view_company_policy', [CompanyPolicyController::class, 'view_company_policy'])->name('view_company_policy');
    Route::get('approve_team_member_leave_application/{id}', [TeamMemberLeaveRequestController::class, 'approve_team_member_leave_application'])->name('approve_team_member_leave_application');
    Route::get('reject_team_member_leave_application/{id}', [TeamMemberLeaveRequestController::class, 'reject_team_member_leave_application'])->name('reject_team_member_leave_application');
    Route::get('view_time_tracker_info/{start_date}/{end_date}', [TimeTrackerController::class, 'view_time_tracker_info'])->name('view_time_tracker_info');
    Route::get('add_time_tracker_info', [TimeTrackerController::class, 'add_time_tracker_info'])->name('add_time_tracker_info');
    Route::get('edit_time_tracker_info/{id}', [TimeTrackerController::class, 'edit_time_tracker_info'])->name('edit_time_tracker_info');
    Route::post('update_time_tracker_info', [TimeTrackerController::class, 'update_time_tracker_info'])->name('update_time_tracker_info');
    Route::post('create_time_tracker_info', [TimeTrackerController::class, 'create_time_tracker_info'])->name('create_time_tracker_info');
    Route::get('Delete_time_tracker_info/{id}', [TimeTrackerController::class, 'DeleteTimeTracker'])->name('delete_time_tracker_info');

    Route::post('add_job_name', [TimeTrackerController::class, 'add_job_name'])->name('add_job_name');
    Route::post('add_project_name', [TimeTrackerController::class, 'add_project_name'])->name('add_project_name');
    Route::get('view-announcement/{id}', [UserDashboardController::class, 'view_announcement'])->name('view-announcement');
    Route::get('change-password', [UserDashboardController::class, 'change_password'])->name('change-password');
    Route::post('change-password-post', [UserDashboardController::class, 'change_password_post'])->name('change-password-post');
    Route::get('mark-as-all-read', [UserDashboardController::class, 'markasallread'])->name('mark-as-all-read-user');
    Route::get('files', [EmployeeFileViewController::class, 'index'])->name('employee.files')->middleware('auth');
});
