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
Route::get('/login', function () {
    return view('auth.login'); 
})->name('login');

Route::get('/register', [UserController::class, 'registration'])->name('register');
Route::post('/register-user', [UserController::class, 'registerUser'])->name('register-user');
Route::post('/login-user', [UserController::class, 'loginUser'])->name('login-user');
Route::get('/firstPage', [UserController::class, 'firstPage'])->name('firstPage');
Route::get('/pages-user-profile', [UserController::class, 'pagesUserProfile'])->name('pages-user-profile');
Route::post('/new-user', [UserController::class, 'newUser'])->name('new-user');
Route::get('/get-entries', [UserController::class, 'getEntries'])->name('get-entries');
Route::get('/get-entry/{id}', [UserController::class, 'getEntry'])->name('get-entry');
Route::post('/delete-entry', [UserController::class, 'deleteEntry'])->name('delete-entry');



