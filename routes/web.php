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
Route::get('/login', [ViewController::class, 'login'])->name('login');
Route::get('/register', [ViewController::class, 'registration'])->name('register');

Route::post('/login-user', [UserController::class, 'loginUser'])->name('login-user');

Route::post('/upsertUser', [UpsertController::class, 'upsertUser'])->name('upsertUser');
Route::get('/fetchRoleId', [UpsertController::class, 'fetchRoleId'])->name('fetchRoleId');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/get-entries', [UserController::class, 'getEntries'])->name('get-entries');
    Route::get('/get-entry/{id}', [UserController::class, 'getEntry'])->name('get-entry');
    Route::post('/getDynamicData', [UserController::class, 'getDynamicData'])->name('getDynamicData');
    Route::post('/getAllRoleData', [UserController::class, 'getAllRoleData'])->name('getAllRoleData');
    Route::post('/getAllUserData', [UserController::class, 'getAllUserData'])->name('getAllUserData');
    Route::post('/getAllMenuData', [UserController::class, 'getAllMenuData'])->name('getAllMenuData');
    Route::get('/getUserData/{id}', [UserController::class, 'getUserData'])->name('getUserData');
    Route::get('/getMenuData/{id}', [UserController::class, 'getMenuData'])->name('getMenuData');
    Route::get('/getRoleData/{id}', [UserController::class, 'getRoleData'])->name('getRoleData');
    Route::get('/getMoreInfo/{id}', [UserController::class, 'getMoreInfo'])->name('getMoreInfo');
    Route::get('/generateSidebarMenu', [UserController::class, 'generateSidebarMenu'])->name('generateSidebarMenu');
    Route::get('/get_all_information', [UserController::class, 'getAllInformation'])->name('get_all_information');
    Route::get('/getPermissionData/{id}', [UserController::class, 'getPermissionData'])->name('getPermissionData');
    Route::post('/getAllPermission/{id}', [UserController::class, 'getAllPermission'])->name('getAllPermission');
    Route::get('/getCredentialForUserData/{id}', [UserController::class, 'getCredentialForUserData'])->name('getCredentialForUserData');


    Route::post('/upsertMenu', [UpsertController::class, 'upsertMenu'])->name('upsertMenu');
    Route::post('/upsertRole', [UpsertController::class, 'upsertRole'])->name('upsertRole');
    Route::post('/checkPermission', [UpsertController::class, 'checkPermission'])->name('checkPermission');
    Route::post('/upsertCredential', [UpsertController::class, 'upsertCredential'])->name('upsertCredential');
    Route::post('/storeDynamicData', [UpsertController::class, 'storeDynamicData'])->name('storeDynamicData');
    Route::post('/insertPermission', [UpsertController::class, 'insertPermission'])->name('insertPermission');
    
    Route::get('/role', [ViewController::class, 'role'])->name('role');
    Route::get('/dashboard', [ViewController::class, 'dashboard'])->name('dashboard');
    Route::get('/user_setup', [ViewController::class, 'userSetup'])->name('user_setup');
    Route::get('/menu_setup', [ViewController::class, 'menuSetup'])->name('menu_setup');
    Route::get('/credential_for_user', [ViewController::class, 'credentialForUser'])->name('credential_for_user');
    Route::get('/credential_for_server', [ViewController::class, 'credentialForServer'])->name('credential_for_server');
    Route::get('/additional_information/{id}', [ViewController::class, 'additional_information'])->name('additional_information');
   
    Route::post('/deleteUserData', [DeleteController::class, 'deleteUserData'])->name('deleteUserData');
    Route::post('/deleteMenuData', [DeleteController::class, 'deleteMenuData'])->name('deleteMenuData');
    Route::post('/deleteRoleData', [DeleteController::class, 'deleteRoleData'])->name('deleteRoleData');
    Route::post('/deleteCredential', [DeleteController::class, 'deleteCredential'])->name('deleteCredential');
    Route::post('/deletePermissionData', [DeleteController::class, 'deletePermissionData'])->name('deletePermissionData');
    Route::post('/deleteCredentialForUserData', [DeleteController::class, 'deleteCredentialForUserData'])->name('deleteCredentialForUserData');
    
});

Route::group(['middleware' => 'can.read'], function () {
    // Routes that require read permission
});
