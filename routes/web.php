<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckInController;
use App\Http\Controllers\EmployeePanelController;
use App\Http\Controllers\ManagerPanelController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->prefix('check-in')->name('hr.check-in.')->group(function () {
    Route::get('/', [CheckInController::class, 'index'])->name('index');
    Route::patch('/clock-in', [CheckInController::class, 'clockIn'])->name('clock-in');
    Route::patch('/clock-out', [CheckInController::class, 'clockOut'])->name('clock-out');
});

Route::middleware('auth')->prefix('my-panel')->name('hr.employee-panel.')->group(function () {
    Route::get('/profile', [EmployeePanelController::class, 'profile'])->name('profile');
    Route::patch('/profile/avatar', [EmployeePanelController::class, 'updateAvatar'])->name('update-avatar');
    Route::get('/leaves', [EmployeePanelController::class, 'myLeaves'])->name('my-leaves');
    Route::get('/leaves/request', [EmployeePanelController::class, 'requestLeave'])->name('request-leave');
    Route::post('/leaves', [EmployeePanelController::class, 'storeLeave'])->name('store-leave');
    Route::delete('/leaves/{leaveId}', [EmployeePanelController::class, 'cancelLeave'])->name('cancel-leave');
    Route::get('/leaves/{leaveId}', [EmployeePanelController::class, 'viewLeave'])->name('view-leave');
});

Route::middleware('auth')->prefix('manager-panel')->name('hr.manager-panel.')->group(function () {
    Route::get('/', [ManagerPanelController::class, 'index'])->name('index');
    Route::get('/my-leaves', [ManagerPanelController::class, 'myLeaves'])->name('my-leaves');
    Route::get('/my-leaves/request', [ManagerPanelController::class, 'requestLeave'])->name('request-leave');
    Route::post('/my-leaves', [ManagerPanelController::class, 'storeLeave'])->name('store-leave');
    Route::get('/my-leaves/{leaveId}', [ManagerPanelController::class, 'viewLeave'])->name('view-leave');
    Route::delete('/my-leaves/{leaveId}', [ManagerPanelController::class, 'cancelLeave'])->name('cancel-leave');
    Route::get('/department-leaves', [ManagerPanelController::class, 'departmentLeaves'])->name('department-leaves');
    Route::patch('/department-leaves/{leaveId}/approve', [ManagerPanelController::class, 'approveLeave'])->name('approve-leave');
    Route::patch('/department-leaves/{leaveId}/reject', [ManagerPanelController::class, 'rejectLeave'])->name('reject-leave');
});
