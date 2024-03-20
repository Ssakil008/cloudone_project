<?php

use Illuminate\Support\Facades\Route;
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

Route::get('/register', [UserController::class, 'registration'])->name('register');
Route::post('/register-user', [UserController::class, 'registerUser'])->name('register-user');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login-user', [UserController::class, 'loginUser'])->name('login-user');
Route::post('/insertCredential', [UserController::class, 'insertCredential'])->name('insertCredential');
Route::post('/addRole', [UserController::class, 'addRole'])->name('addRole');
Route::post('/insertPermission', [UserController::class, 'insertPermission'])->name('insertPermission');
Route::get('/get-entries', [UserController::class, 'getEntries'])->name('get-entries');
Route::get('/getAllUserData', [UserController::class, 'getAllUserData'])->name('getAllUserData');
Route::get('/getAllRoleData', [UserController::class, 'getAllRoleData'])->name('getAllRoleData');
Route::get('/get-entry/{id}', [UserController::class, 'getEntry'])->name('get-entry');
Route::get('/getUserData/{id}', [UserController::class, 'getUserData'])->name('getUserData');
Route::get('/getRoleData/{id}', [UserController::class, 'getRoleData'])->name('getRoleData');
Route::get('/getAllPermission/{id}', [UserController::class, 'getAllPermission'])->name('getAllPermission');
Route::post('/deleteCredential', [UserController::class, 'deleteCredential'])->name('deleteCredential');
Route::post('/deleteUserData', [UserController::class, 'deleteUserData'])->name('deleteUserData');
Route::post('/deleteRoleData', [UserController::class, 'deleteRoleData'])->name('deleteRoleData');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/user_profile', [UserController::class, 'userProfile'])->name('user_profile');
    Route::get('/user_setup', [UserController::class, 'userSetup'])->name('user_setup');
    Route::get('/role', [UserController::class, 'role'])->name('role');
});
