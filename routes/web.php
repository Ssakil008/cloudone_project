<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
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

Route::get('/generateSidebarMenu', [UserController::class, 'generateSidebarMenu'])->name('generateSidebarMenu');
Route::get('/register', [UserController::class, 'registration'])->name('register');
Route::post('/register-user', [UserController::class, 'registerUser'])->name('register-user');
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login-user', [UserController::class, 'loginUser'])->name('login-user');
Route::post('/insertCredential', [UserController::class, 'insertCredential'])->name('insertCredential');
Route::post('/addRole', [UserController::class, 'addRole'])->name('addRole');
Route::post('/insertPermission', [UserController::class, 'insertPermission'])->name('insertPermission');
Route::get('/get-entries', [UserController::class, 'getEntries'])->name('get-entries');
Route::get('/getAllRoleData', [UserController::class, 'getAllRoleData'])->name('getAllRoleData');
Route::get('/getAllUserData', [UserController::class, 'getAllUserData'])->name('getAllUserData');
Route::get('/get-entry/{id}', [UserController::class, 'getEntry'])->name('get-entry');
Route::get('/getUserData/{id}', [UserController::class, 'getUserData'])->name('getUserData');
Route::get('/getRoleData/{id}', [UserController::class, 'getRoleData'])->name('getRoleData');
Route::get('/getPermissionData/{id}', [UserController::class, 'getPermissionData'])->name('getPermissionData');
Route::get('/getAllPermission/{id}', [UserController::class, 'getAllPermission'])->name('getAllPermission');
Route::post('/fetchUserPermissions', [UserController::class, 'fetchUserPermissions'])->name('fetchUserPermissions');
Route::post('/deleteCredential', [UserController::class, 'deleteCredential'])->name('deleteCredential');
Route::post('/deleteUserData', [UserController::class, 'deleteUserData'])->name('deleteUserData');
Route::post('/deleteRoleData', [UserController::class, 'deleteRoleData'])->name('deleteRoleData');
Route::post('/deletePermissionData', [UserController::class, 'deletePermissionData'])->name('deletePermissionData');
Route::post('/storeDynamicData', [UserController::class, 'storeDynamicData'])->name('storeDynamicData');
Route::get('/getDynamicData', [UserController::class, 'getDynamicData'])->name('getDynamicData');
Route::post('/deleteCredentialForUserData', [UserController::class, 'deleteCredentialForUserData'])->name('deleteCredentialForUserData');
Route::get('/getCredentialForUserData/{id}', [UserController::class, 'getCredentialForUserData'])->name('getCredentialForUserData');
Route::get('/get_all_information', [UserController::class, 'getAllInformation'])->name('get_all_information');
Route::get('/getMoreInfo/{id}/{name}', [UserController::class, 'getMoreInfo'])->name('getMoreInfo');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/credential_for_server', [UserController::class, 'credentialForServer'])->name('credential_for_server');
    Route::get('/credential_for_user', [UserController::class, 'credentialForUser'])->name('credential_for_user');
    Route::get('/user_setup', [UserController::class, 'userSetup'])->name('user_setup');
    Route::get('/role', [UserController::class, 'role'])->name('role');
    Route::get('/additional_information', [UserController::class, 'additionalInformation'])->name('additional_information');

});
