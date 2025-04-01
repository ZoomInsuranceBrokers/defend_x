<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CompanyAdminController;
use App\Http\Controllers\UserController;
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
    return redirect()->route('login');
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['superadmin'])->prefix('superadmin')->group(function () {

    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Company Routes /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::get('/company-details', [SuperAdminController::class, 'company_details'])->name('superadmin.company_details');

    Route::get('/add-company', [SuperAdminController::class, 'add_company'])->name('superadmin.add_company');

    Route::post('/add-company', [SuperAdminController::class, 'add_company_post'])->name('superadmin.add_company_post');

    Route::get('edit-company/{id}', [SuperAdminController::class, 'edit_company'])->name('superadmin.edit.company');

    Route::post('/edit-company/{id}', [SuperAdminController::class, 'update_company'])->name('superadmin.update_company');

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// User Routes /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::get('/user-details', [SuperAdminController::class, 'user_details'])->name('superadmin.user_details');

    Route::get('/add-user', [SuperAdminController::class, 'add_user'])->name('superadmin.add_user');

    Route::post('/add-user', [SuperAdminController::class, 'add_user_post'])->name('superadmin.add_user_post');

    Route::get('edit-user/{id}', [SuperAdminController::class, 'edit_user'])->name('superadmin.edit.user');

    Route::post('/edit-user/{id}', [SuperAdminController::class, 'update_user'])->name('superadmin.update_user');

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////// Vendor Routes /////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::get('/vendor-details', [SuperAdminController::class, 'vendor_details'])->name('superadmin.vendor_details');

    Route::get('/add-vendor', [SuperAdminController::class, 'add_vendor'])->name('superadmin.add_vendor');

    Route::post('/add-vendor', [SuperAdminController::class, 'add_vendor_post'])->name('superadmin.add_vendor_post');

    Route::get('edit-vendor/{id}', [SuperAdminController::class, 'edit_vendor'])->name('superadmin.edit.vendor');

    Route::post('/edit-vendor/{id}', [SuperAdminController::class, 'update_vendor'])->name('superadmin.update_vendor');

});

Route::middleware(['companyadmin'])->prefix('companyadmin')->group(function () {
    Route::get('/dashboard', [CompanyAdminController::class, 'dashboard'])->name('companyadmin.dashboard');
});

Route::middleware(['user'])->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user.dashboard');
});
