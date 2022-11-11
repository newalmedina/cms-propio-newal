<?php

use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminUserProfileController;
use App\Http\Controllers\Auth\FrontRegisterUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LocalizationController;
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

/** begin -- de autenticacion */
Auth::routes(['verify' => true]);

Route::post('/register', [FrontRegisterUserController::class, 'create'])
    ->middleware('guest');
Route::get('/register/verify/{confirmation_code}', [FrontRegisterUserController::class, 'verify'])
    ->middleware('guest');
/** end -- de autenticacion */

Route::group(array('prefix' => ''), function () {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    //   Route::resource('alarms', 'FrontAlarmsController');
});


//change language
Route::get('lang/{locale}', [LocalizationController::class, 'index']);


//General Routes
Route::group(array('prefix' => 'admin', 'middleware' => ['auth', 'verified']), function () {

    //Admin Profile
    Route::get('/profile', [AdminUserProfileController::class, 'edit']);
    Route::get('/profile/getphoto/{photo}', [AdminUserProfileController::class, 'getPhoto'])->name("admin.getPhoto");
    Route::post('/profile/store', [AdminUserProfileController::class, 'store'])->name("admin.updateProfile");

    //Admin Categories
    Route::get('/categories', [AdminCategoryController::class, 'index']);

    //Admin Roles
    Route::get('/roles', [AdminRoleController::class, 'index']);
    Route::get('/roles/{id}/edit', [AdminRoleController::class, 'edit'])->name('admin.roles.edit');
    Route::patch('/roles/{id}', [AdminRoleController::class, 'update'])->name('admin.roles.update');
    Route::get('/roles/permissions/{id}', [AdminRoleController::class, 'editPermissions'])->name('admin.roles.editPermissions');
    Route::patch('/roles/permissions/{id}', [AdminRoleController::class, 'updatePermissions'])->name('admin.permissions.update');

    //admin users
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::get('/users/change-state/{id}', [AdminUserController::class, 'changeState'])->name('admin.users.changeState');
    Route::patch('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::post('/users/list', [AdminUserController::class, 'getData'])->name('admin.users.getData');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/users/roles/{id}', [AdminUserController::class, 'editRoles'])->name('admin.users.editRoles');
    Route::patch('/users/roles/{id}', [AdminUserController::class, 'updateRoles'])->name('admin.users.updateRoles');
});
