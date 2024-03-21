<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\CredentialForServer;
use App\Models\Role;
use App\Models\RolePermission;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function registration()
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        }
        return view('auth.register');
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->intended(route('dashboard'));
        }
        return view('auth.login');
    }

    public function registerUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|min:11|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()], 422);
        }

        // Retrieve the id from the request
        $id = $request->input('userId');

        // Check if id is empty to determine if it's an insert or update
        if (empty($id)) {
            //insertion
            $user = new User();
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password);
            $userResult = $user->save();

            // $entry = new CredentialForServer();
            // $entry->credential_for = $request->credential_for;
            // $entry->email = $request->email;
            // $entry->mobile = $request->mobile;
            // $entry->url = $request->url;
            // $entry->ip_address = $request->ip_address;
            // $entry->username = $request->username;
            // $entry->password = $request->password;
            // $entryResult = $entry->save();
        } else {
            // Update
            $user = User::find($id);
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password);
            $userResult = $user->save();

            // $entry = CredentialForServer::find($id);
            // $entry->credential_for = $request->credential_for;
            // $entry->email = $request->email;
            // $entry->mobile = $request->mobile;
            // $entry->url = $request->url;
            // $entry->ip_address = $request->ip_address;
            // $entry->username = $request->username;
            // $entry->password = $request->password;
            // $entryResult = $entry->save();
        }

        // if ($userResult && $entryResult) {
        if ($userResult) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'fail' => 'Something went wrong']);
        }
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credential = $request->only('email', 'password');

        if (Auth::attempt($credential)) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['fail' => 'The email is not recognized']);
        }
    }

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function userProfile()
    {
        return view('pages.credential_for_server');
    }

    public function insertCredential(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credential_for' => 'required|string|max:255|unique:credential_for_servers',
            'email' => 'required|string|email|max:255|unique:credential_for_servers',
            'mobile' => 'required|string|min:11',
            'url' => 'required|string|url',
            'ip_address' => 'required|ip',
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()], 422);
        }

        // Retrieve the id from the request
        $id = $request->input('entryId');

        // Check if id is empty to determine if it's an insert or update
        if (empty($id)) {
            // // Insertion
            // $user = new User();
            // $user->email = $request->email;
            // $user->mobile = $request->mobile;
            // $user->password = Hash::make($request->password);
            // $userResult = $user->save();

            $entry = new CredentialForServer();
            $entry->credential_for = $request->credential_for;
            $entry->email = $request->email;
            $entry->mobile = $request->mobile;
            $entry->url = $request->url;
            $entry->ip_address = $request->ip_address;
            $entry->username = $request->username;
            $entry->password = $request->password;
            $entryResult = $entry->save();
        } else {
            // // Update
            // $user = User::find($id);
            // $user->email = $request->email;
            // $user->mobile = $request->mobile;
            // $user->password = Hash::make($request->password);
            // $userResult = $user->save();

            $entry = CredentialForServer::find($id);
            $entry->credential_for = $request->credential_for;
            $entry->email = $request->email;
            $entry->mobile = $request->mobile;
            $entry->url = $request->url;
            $entry->ip_address = $request->ip_address;
            $entry->username = $request->username;
            $entry->password = $request->password;
            $entryResult = $entry->save();
        }

        // if ($userResult && $entryResult) {
        if ($entryResult) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'fail' => 'Something went wrong']);
        }
    }


    public function addRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|max:255|unique:roles',
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()], 422);
        }

        $id = $request->input('roleId');

        if (empty($id)) {
            //insertion
            $role = new Role();
            $role->role = $request->role;
            $role->description = $request->description;
            $roleResult = $role->save();
        } else {
            // Update
            $role = Role::find($id);
            $role->role = $request->role;
            $role->description = $request->description;
            $roleResult = $role->save();
        }

        if ($roleResult) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'fail' => 'Something went wrong']);
        }
    }

    public function insertPermission(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'role_id' => 'required|integer',
            'module' => 'required|string',
            'read' => 'nullable|string',
            'create' => 'nullable|string',
            'edit' => 'nullable|string',
            'delete' => 'nullable|string',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'errors' => $validatedData->errors()->toArray()], 422);
        }

        $id = $request->input('permissionId');

        if (empty($id)) {
            // Insertion
            $rolePermission = new RolePermission();
        } else {
            // Update
            $rolePermission = RolePermission::find($id);
            if (!$rolePermission) {
                return response()->json(['success' => false, 'fail' => 'Role permission not found'], 404);
            }
        }

        // Update the role permission attributes
        $rolePermission->role_id = $request->input('role_id');
        $rolePermission->module = $request->input('module');
        $rolePermission->read = $request->input('read') ?? 'no';
        $rolePermission->create = $request->input('create') ?? 'no';
        $rolePermission->edit = $request->input('edit') ?? 'no';
        $rolePermission->delete = $request->input('delete') ?? 'no';

        // Save the role permission to the database
        if ($rolePermission->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'fail' => 'Something went wrong'], 500);
        }
    }


    public function getEntries()
    {
        $entries = CredentialForServer::all();

        return response()->json(['data' => $entries]);
    }

    public function getAllUserData()
    {
        $users = User::where('email', '!=', 'monir.uddincloudone@gmail.com')->get();
    
        return response()->json(['data' => $users]);
    }    


    public function getAllRoleData()
    {
        $roles = Role::all();

        return response()->json(['data' => $roles]);
    }


    public function getEntry($id)
    {
        $entry = CredentialForServer::find($id);

        if ($entry) {
            return response()->json(['data' => $entry]);
        } else {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    }

    public function getUserData($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['data' => $user]);
        } else {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    }

    public function getRoleData($id)
    {
        $role = Role::find($id);

        if ($role) {
            return response()->json(['data' => $role]);
        } else {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    }

    public function getPermissionData($id)
    {
        $permission = RolePermission::find($id);

        if ($permission) {
            return response()->json(['data' => $permission]);
        } else {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    }

    public function getAllPermission($id)
    {
        // Find permissions associated with the given role ID
        $permissions = RolePermission::where('role_id', $id)->get();

        if ($permissions->isNotEmpty()) {
            // If permissions are found, return them as JSON response
            return response()->json(['data' => $permissions]);
        } else {
            // If no permissions are found, return a 404 error response
            return response()->json(['error' => 'No permissions found for the given role ID'], 404);
        }
    }

    public function deleteCredential(Request $request)
    {
        $Id = $request->input('entryId');


        // Validate that the entry and user exist
        $entry = CredentialForServer::find($Id);
        // $user = User::find($Id);

        // if (!$entry || !$user) {
        if (!$entry) {
            return response()->json(['success' => false, 'error' => 'Entry or user not found']);
        }

        try {
            // Start a database transaction
            DB::beginTransaction();

            // Delete the entry from the "entries" table
            $entry->delete();

            // Delete the corresponding user from the "users" table
            // $user->delete();

            // Commit the transaction
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            // An error occurred, rollback the transaction
            DB::rollBack();

            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function deleteUserData(Request $request)
    {
        $Id = $request->input('userId');

        $userId = User::find($Id);

        if (!$userId) {
            return response()->json(['success' => false, 'error' => 'User not found']);
        }

        $userId->delete();

        return response()->json(['success' => true]);
    }

    public function deleteRoleData(Request $request)
    {
        $Id = $request->input('roleId');

        $roleId = Role::find($Id);

        if (!$roleId) {
            return response()->json(['success' => false, 'error' => 'User not found']);
        }

        $roleId->delete();

        return response()->json(['success' => true]);
    }

    public function deletePermissionData(Request $request)
    {
        $Id = $request->input('permissionId');

        $permissionId = RolePermission::find($Id);

        if (!$permissionId) {
            return response()->json(['success' => false, 'error' => 'User not found']);
        }

        $permissionId->delete();

        return response()->json(['success' => true]);
    }

    public function logout()
    {
        Session::flush();
        return response()->json(['success' => true]);
    }

    public function userSetup()
    {
        return view('pages.user_setup');
    }

    public function role()
    {
        return view('pages.role');
    }
}
