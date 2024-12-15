<?php

use App\Http\Controllers\Apps\CustomerManagementController;
use App\Http\Controllers\Apps\GodownManagement;
use App\Http\Controllers\Apps\NewsManagementController;
use App\Http\Controllers\Apps\PermissionManagementController;
use App\Http\Controllers\Apps\RentsManagement;
use App\Http\Controllers\Apps\ReturnmaterialsController;
use App\Http\Controllers\Apps\RoleManagementController;
use App\Http\Controllers\Apps\UserManagementController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomePageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::name('user-management.')->group(function () {
        Route::resource('/user-management/users', UserManagementController::class);
        Route::resource('/user-management/roles', RoleManagementController::class);
        Route::resource('/user-management/permissions', PermissionManagementController::class);
    });
    Route::name('news-management.')->group(function () {
        Route::resource('/news-management/news', NewsManagementController::class);

    });

    Route::name('customer-management.')->group(function() {
        Route::resource('/customer-management/customers', CustomerManagementController::class);
    });
    Route::name('godown-management.')->group(function() {
        Route::resource('/godown-management/materials', GodownManagement::class);
    });
    Route::name('rents-management.')->group(function () {
        Route::resource('/rents-management/rent-material', RentsManagement::class);
        Route::get('/rents-management/return-rent-material', [ReturnmaterialsController::class, 'index'])->name('return-rent-material.index');
        Route::get('/rents-management/today-return-rent-material', [ReturnmaterialsController::class, 'getreturns'])->name('today-return-rent-material.getreturns');
        Route::get('/rents-management/dues',[RentsManagement::class,'dues'])->name('dues');
    });
    Route::get('/news-management/manage', [NewsManagementController::class, 'manage'])->name('news-management.manages');
});
Route::name('costal-connect.')->group(function () {
    Route::get('/login', [HomePageController::class, 'index']); 
});

Route::get('/error', function () {
    abort(500);
});

Route::get('/auth/redirect/{provider}', [SocialiteController::class, 'redirect']);

require __DIR__ . '/auth.php';