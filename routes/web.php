<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\DeleteController;
use App\Http\Controllers\UpsertController;
use Illuminate\Http\Request;


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

Route::get('/register', [ViewController::class, 'registration'])->name('register');
Route::get('/login', [ViewController::class, 'login'])->name('login');
Route::post('/login-user', [UserController::class, 'loginUser'])->name('login-user');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
    Route::get('/credential_for_server', [ViewController::class, 'credentialForServer'])->name('credential_for_server');
    Route::get('/credential_for_user', [ViewController::class, 'credentialForUser'])->name('credential_for_user');
    Route::get('/user_setup', [ViewController::class, 'userSetup'])->name('user_setup');
    Route::get('/role', [ViewController::class, 'role'])->name('role');
    Route::get('/additional_information/{id}', [ViewController::class, 'additional_information'])->name('additional_information');
    Route::get('/generateSidebarMenu', [UserController::class, 'generateSidebarMenu'])->name('generateSidebarMenu');
    Route::post('/upsertCredential', [UpsertController::class, 'upsertCredential'])->name('upsertCredential');
    Route::post('/upsertRole', [UpsertController::class, 'upsertRole'])->name('upsertRole');
    Route::post('/checkPermission', [UpsertController::class, 'checkPermission'])->name('checkPermission');
    Route::post('/insertPermission', [UserController::class, 'insertPermission'])->name('insertPermission');
    Route::get('/get-entries', [UserController::class, 'getEntries'])->name('get-entries');
    Route::get('/getAllRoleData', [UserController::class, 'getAllRoleData'])->name('getAllRoleData');
    Route::get('/getAllUserData', [UserController::class, 'getAllUserData'])->name('getAllUserData');
    Route::get('/get-entry/{id}', [UserController::class, 'getEntry'])->name('get-entry');
    Route::get('/getUserData/{id}', [UserController::class, 'getUserData'])->name('getUserData');
    Route::get('/getRoleData/{id}', [UserController::class, 'getRoleData'])->name('getRoleData');
    Route::get('/getPermissionData/{id}', [UserController::class, 'getPermissionData'])->name('getPermissionData');
    Route::get('/getAllPermission/{id}', [UserController::class, 'getAllPermission'])->name('getAllPermission');
    Route::post('/deleteCredential', [DeleteController::class, 'deleteCredential'])->name('deleteCredential');
    Route::post('/deleteUserData', [DeleteController::class, 'deleteUserData'])->name('deleteUserData');
    Route::post('/deleteRoleData', [DeleteController::class, 'deleteRoleData'])->name('deleteRoleData');
    Route::post('/deletePermissionData', [DeleteController::class, 'deletePermissionData'])->name('deletePermissionData');
    Route::post('/storeDynamicData', [UpsertController::class, 'storeDynamicData'])->name('storeDynamicData');
    Route::post('/upsertUser', [UpsertController::class, 'upsertUser'])->name('upsertUser');
    Route::get('/fetchRoleId', [UpsertController::class, 'fetchRoleId'])->name('fetchRoleId');
    Route::get('/getDynamicData', [UserController::class, 'getDynamicData'])->name('getDynamicData');
    Route::post('/deleteCredentialForUserData', [DeleteController::class, 'deleteCredentialForUserData'])->name('deleteCredentialForUserData');
    Route::get('/getCredentialForUserData/{id}', [UserController::class, 'getCredentialForUserData'])->name('getCredentialForUserData');
    Route::get('/get_all_information', [UserController::class, 'getAllInformation'])->name('get_all_information');
    Route::get('/getMoreInfo/{id}', [UserController::class, 'getMoreInfo'])->name('getMoreInfo');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});
