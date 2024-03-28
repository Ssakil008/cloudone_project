<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\CredentialForServer;
use App\Models\Role;
use App\Models\Permission;
use App\Models\UserRole;
use App\Models\Menu;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


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
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'mobile' => 'required|string|min:11|unique:users',
            // 'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $request->input('userId'),
            'mobile' => 'required|string|min:11|unique:users,mobile,' . $request->input('userId'),
            'password' => $request->filled('userId') ? 'nullable|string|min:8' : 'required|string|min:8',
            'roleId' => 'exists:roles,id', // Add validation for roleId
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Empty Field or Duplicate Email/Mobile']);
        }

        $id = $request->input('userId');

        if (empty($id)) {
            // Insertion
            $user = new User();
        } else {
            // Update
            $user = User::find($id);
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User Id not found']);
            }
        }

        // Insert into users table
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        // $user->password = Hash::make($request->password);
        // Update the password only if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        if (!empty($id)) {
            // If userId exists, update the user_role record
            $user->roles()->sync($request->roleId); // Update existing role
        } else {
            // If userId does not exist, attach the role to the user
            $user->roles()->attach($request->roleId);
        }

        // return response()->json(['success' => true, 'message' => 'User added successfully']);
        return response()->json(['success' => true, 'message' => 'User ' . ($id ? 'updated' : 'added') . ' successfully']);
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
        // Retrieve the id from the request
        $id = $request->input('entryId');

        $rules = [
            'credential_for' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|string|min:11',
            'url' => 'required|string|url',
            'ip_address' => 'required|ip',
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ];

        // Add unique validation rules with exceptions for updates
        if (empty($id)) {
            // For insertions, no need to exclude any IDs
            $rules['email'] .= '|unique:credential_for_servers';
            $rules['mobile'] .= '|unique:credential_for_servers';
        } else {
            // For updates, exclude the current entry's ID from the unique check
            $rules['email'] .= '|unique:credential_for_servers,email,' . $id;
            $rules['mobile'] .= '|unique:credential_for_servers,mobile,' . $id;
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()], 422);
        }

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
            'menu' => 'required|string',
            'read' => 'nullable|string',
            'create' => 'nullable|string',
            'edit' => 'nullable|string',
            'delete' => 'nullable|string',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation failed']);
        }

        $id = $request->input('permissionId');

        if (empty($id)) {
            // Insertion
            $rolePermission = new Permission();
        } else {
            // Update
            $rolePermission = Permission::find($id);
            if (!$rolePermission) {
                return response()->json(['success' => false, 'message' => 'Role permission not found']);
            }
        }

        // Update the role permission attributes
        $rolePermission->role_id = $request->input('role_id');
        $rolePermission->menu_id = $request->input('menu');
        $rolePermission->read = $request->input('read') ?? 'no';
        $rolePermission->create = $request->input('create') ?? 'no';
        $rolePermission->edit = $request->input('edit') ?? 'no';
        $rolePermission->delete = $request->input('delete') ?? 'no';

        // Save the role permission to the database
        if ($rolePermission->save()) {
            return response()->json(['success' => true, 'message' => 'Permission added successfully']);
        } else {
            // Permission denied
            return response()->json(['success' => false, 'message' => 'Failed to add permission']);
        }
    }


    // public function getEntries()
    // {
    //     $credentials = CredentialForServer::query();

    //     return DataTables::of($credentials)
    //         ->make(true); // Enable server-side processing
    // }

    public function getEntries()
    {
        $queries = CredentialForServer::query()->get();

        // DataTables expects specific JSON response structure
        $data = [];
        foreach ($queries as $query) {
            $data[] = [
                'id' => $query->id,
                'credential_for' => $query->credential_for,
                'email' => $query->email, // Access user email through relationship
                'mobile' => $query->mobile,
                'url' => $query->url,
                'ip_address' => $query->ip_address,
                'username' => $query->username,
                'password' => $query->password,
                // Add other columns as needed
            ];
        }

        return DataTables::of($data)->make(true);
    }


    public function getAllUserData()
    {
        $users = User::with('user_role.role')
            ->where('email', '!=', 'monir.uddincloudone@gmail.com')
            ->get();

        // Modify user data with role information before returning
        $users->each(function ($user) {
            if ($user->user_role) {
                $user->role = $user->user_role->role->role; // Access role name
            } else {
                $user->role = ''; // Set default value if no role assigned
            }
        });

        return DataTables::of($users)->make(true);
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
        $user = User::with('user_role.role')->find($id);

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
        $permission = Permission::with('menu')->find($id); // Assuming 'menu' is the relationship between Permission and Menu models

        if ($permission) {
            return response()->json(['data' => $permission]);
        } else {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    }


    public function getAllPermission($id)
    {
        // Find permissions associated with the given role ID and eager load the related menu data
        $permissions = Permission::with('menu')->where('role_id', $id)->get();

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
        $entryId = $request->input('entryId');
        $userId = $request->input('userId');
        $menu_id = $request->input('menu_id');

        // Retrieve the user's role ID from the user_role pivot table
        $userRole = UserRole::where('user_id', $userId)->first();

        if (!$userRole) {
            return response()->json(['success' => false, 'message' => 'User role not found']);
        }

        $roleId = $userRole->role_id;

        // Check if the user's role has the 'delete' permission for the specified module
        $permissions = Permission::where('role_id', $roleId)
            ->where('menu_id', $menu_id)
            ->first();

        if ($permissions && $permissions->delete === 'yes') {
            // User has permission, proceed with deletion
            $entry = CredentialForServer::find($entryId);
            if (!$entry) {
                return response()->json(['success' => false, 'message' => 'Credential not found']);
            }

            $entry->delete();
            return response()->json(['success' => true, 'message' => 'Entry deleted successfully']);
        } else {
            // Permission denied
            return response()->json(['success' => false, 'message' => 'Permission denied']);
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

        $permissionId = Permission::find($Id);

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

    public function fetchUserPermissions(Request $request)
    {
        $userId = auth()->id();
        $menu_id = $request->input('menu_id');

        // Retrieve the user's role ID from the user_role pivot table
        $userRole = UserRole::where('user_id', $userId)->first();

        if (!$userRole) {
            return response()->json(['error' => 'User role not found'], 404);
        }

        $roleId = $userRole->role_id;

        // Fetch permissions based on the user's role ID and module
        $permissions = Permission::where('role_id', $roleId)
            ->where('menu_id', $menu_id)
            ->first();

        if (!$permissions) {
            return response()->json(['error' => 'Permissions not found'], 404);
        }

        return response()->json(['permissions' => $permissions]);
    }

    function generateSidebarMenu()
    {
        $userId = auth()->id();
        $userRole = UserRole::where('user_id', $userId)->first();

        if (!$userRole) {
            // Handle the case where user role is not found
            return [];
        }

        // Step 2: Find all permissions associated with the retrieved role ID
        $permissions = Permission::where('role_id', $userRole->role_id)->get();

        $sidebarMenu = [];

        // Step 3-5: Iterate over each permission and retrieve menu name and link
        foreach ($permissions as $permission) {
            // Find the menu associated with the permission
            $menu = Menu::find($permission->menu_id);

            if ($menu) {
                // If menu exists, add its name and link to the sidebar menu
                $sidebarMenu[] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'link' => $menu->link,
                ];
            }
        }
        return response()->json(['sidebarMenu' => $sidebarMenu]);
    }
}
