<?php

use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\LeaveRequestController;
use App\Http\Controllers\User\TeamMemberLeaveRequestController;
use App\Http\Controllers\User\CheckInController;
use App\Http\Controllers\User\ModuleController;
use App\Http\Controllers\User\EmployeeAttendanceController;
use App\Http\Controllers\User\CompanyPolicyController;
use App\Http\Controllers\User\TimeTrackerController;
use App\Http\Controllers\User\EmployeeFileViewController;
use Illuminate\Support\Facades\Route;

   Route::get('/leave/approve/{leave_id}/{token}', [LeaveRequestController::class, 'approveLeave'])->name('leave.approve');
Route::get('/leave/disapprove/{leave_id}/{token}', [LeaveRequestController::class, 'disapproveLeave'])->name('leave.disapprove');

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
