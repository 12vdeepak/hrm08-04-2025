<?php

use App\Http\Controllers\HR\DashboardController as HRDashboardController;
use App\Http\Controllers\HR\EmployeeLeaveRequestController;
use App\Http\Controllers\HR\EmployeeAttributes;
use App\Http\Controllers\HR\EmployeeController;
use App\Http\Controllers\HR\HolidayController;
use App\Http\Controllers\HR\HrCompanyPolicyController;
use App\Http\Controllers\HR\AnnouncementController;
use App\Http\Controllers\HR\AttendanceController;
use App\Http\Controllers\HR\EmployeeDetailController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\HR\ViewTimeTrackerController;
use App\Http\Controllers\User\CheckInController;
use App\Http\Controllers\User\TimeTrackerController;
use App\Http\Controllers\HR\FileUploadController;
use App\Http\Controllers\HR\LeaveController;
use Illuminate\Support\Facades\Route;

Route::get('/leave/approve/{leave_id}/{token}', [LeaveController::class, 'approveLeave'])->name('leave.approve');
Route::get('/leave/disapprove/{leave_id}/{token}', [LeaveController::class, 'disapproveLeave'])->name('leave.disapprove');
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
