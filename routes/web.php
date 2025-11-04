<?php

use App\Http\Controllers\HR\DashboardController as HRDashboardController;
use App\Http\Controllers\ActivityTrackerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SuperAdmin\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\TimeTrackerController;
use App\Http\Controllers\GmailController;


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

require __DIR__.'/user.php';
require __DIR__.'/hr.php';

Route::get('/ba/update-project-date/{timeTracker}', [TimeTrackerController::class, 'showUpdateForm'])
    ->name('ba.update.project.date.form'); // can add middleware if needed

// Save the date (POST)
Route::post('/ba/update-project-date/{timeTracker}', [TimeTrackerController::class, 'updateProjectStartDate'])
    ->name('ba.update.project.date'); // keep your permission middleware here if you have it

   Route::get('/projects/{id}/start-date', [TimeTrackerController::class, 'getProjectStartDate'])
    ->name('projects.start-date');

// BA End Date routes
Route::get('/ba/update-project-end-date/{timeTracker}', [TimeTrackerController::class, 'showUpdateEndDateForm'])
    ->name('ba.update.project.enddate.form');
Route::post('/ba/update-project-end-date/{timeTracker}', [TimeTrackerController::class, 'updateProjectEndDate'])
    ->name('ba.update.project.enddate');







