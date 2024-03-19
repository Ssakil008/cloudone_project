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
Route::post('/new-user', [UserController::class, 'newUser'])->name('new-user');
Route::get('/get-entries', [UserController::class, 'getEntries'])->name('get-entries');
Route::get('/getAllUserData', [UserController::class, 'getAllUserData'])->name('getAllUserData');
Route::get('/get-entry/{id}', [UserController::class, 'getEntry'])->name('get-entry');
Route::get('/getUserData/{id}', [UserController::class, 'getUserData'])->name('getUserData');
Route::post('/delete-entry', [UserController::class, 'deleteEntry'])->name('delete-entry');
Route::post('/deleteUserData', [UserController::class, 'deleteUserData'])->name('deleteUserData');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/user_profile', [UserController::class, 'userProfile'])->name('user_profile');
    Route::get('/user_setup', [UserController::class, 'userSetup'])->name('user_setup');
});
