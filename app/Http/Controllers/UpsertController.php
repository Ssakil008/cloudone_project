<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\CredentialForUser;
use App\Models\CredentialForServer;
use App\Models\AdditionalInformation;
use App\Models\UserRole;
use App\Models\Permission;


class UpsertController extends Controller
{
    public function upsertUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'mobile' => 'required|string|min:11|unique:users',
            // 'password' => 'required|string|min:8',
            'username' => 'required|string|max:255',
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
        $user->username = $request->username;
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

    public function storeDynamicData(Request $request)
    {
        DB::beginTransaction(); // Start transaction

        try {
            // Separate predefined fields and dynamic fields
            $predefinedFields = $request->except('fields');
            $dynamicFields = $request->input('fields');

            // Check if user ID is provided for updating existing user data
            $userId = $request->input('userId');

            // Handle predefined fields (direct insert or update)
            if ($userId) {
                $user = CredentialForUser::find($userId);
                $user->fill($predefinedFields);
                $user->save();
            } else {
                $user = CredentialForUser::create($predefinedFields);
            }

            if (!empty($dynamicFields)) {
                // Extract dynamic field names and values (similar to existing code)
                $fieldNames = [];
                $fieldValues = [];
                foreach ($dynamicFields as $index => $field) {
                    if (isset($field['field_name'])) {
                        $fieldNames[] = $field['field_name'];
                    }
                    if (isset($field['field_value'])) {
                        $fieldValues[] = $field['field_value'];
                    }
                }

                $lastInsertedId = $user->id;
                foreach ($fieldNames as $index => $fieldName) {
                    $newData = new AdditionalInformation();
                    $newData->credential_for_user_id = $lastInsertedId;
                    $newData->field_name = $fieldName;
                    $newData->field_value = $fieldValues[$index];
                    $newData->save();
                }
            }

            DB::commit(); // Commit transaction

            return response()->json(['success' => true, 'message' => 'Data stored successfully.']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on exception

            // Check if the exception is due to unique constraint violation
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                // Extract the duplicate entry value from the error message
                preg_match("/Duplicate entry '(.+)' for key/", $e->getMessage(), $matches);
                $errorMessage = "Duplicate entry '{$matches[1]}'";
            } else {
                // For other types of exceptions, use the default error message
                $errorMessage = $e->getMessage();
            }

            return response()->json(['success' => false, 'message' => 'Failed to store data.', 'error' => $errorMessage], 500);
        }
    }

    public function upsertRole(Request $request)
    {
        // Retrieve the id from the request
        $id = $request->input('roleId');
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|max:255|unique:roles,role,' . $id,
            'description' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()->toArray()], 422);
        }

        if (empty($id)) {
            // Insertion
            $role = new Role();
            $role->role = $request->role;
            $role->description = $request->description;
            $roleResult = $role->save();

            // Return response for insertion
            if ($roleResult) {
                return response()->json(['success' => true, 'message' => 'Role added successfully.']);
            } else {
                return response()->json(['success' => false, 'fail' => 'Failed to add role.']);
            }
        } else {
            // Update
            $role = Role::find($id);
            $role->role = $request->role;
            $role->description = $request->description;
            $roleResult = $role->save();

            // Return response for update
            if ($roleResult) {
                return response()->json(['success' => true, 'message' => 'Role updated successfully.']);
            } else {
                return response()->json(['success' => false, 'fail' => 'Failed to update role.']);
            }
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

    public function upsertCredential(Request $request)
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

            if ($entryResult) {
                return response()->json(['success' => true, 'message' => 'New entry added successfully.']);
            } else {
                return response()->json(['success' => false, 'fail' => 'Failed to add new entry.']);
            }
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

            if ($entryResult) {
                return response()->json(['success' => true, 'message' => 'Entry updated successfully.']);
            } else {
                return response()->json(['success' => false, 'fail' => 'Failed to update entry.']);
            }
        }
    }

    public function checkPermission(Request $request)
    {
        $menuId = $request->input('menuId');
        $action = $request->input('action');
        $userId = Auth::id();


        // Retrieve the user's role ID from the user_role pivot table
        $userRole = UserRole::where('user_id', $userId)->first();

        if (!$userRole) {
            return response()->json(['success' => false, 'message' => 'User role not found']);
        }

        $roleId = $userRole->role_id;

        // Check if the user's role has the specified permission for the specified module
        $permissions = Permission::where('role_id', $roleId)
            ->where('menu_id', $menuId)
            ->first();

        if ($permissions && $permissions->$action === 'yes') {
            // User has permission
            return response()->json(['success' => true]);
        } else {
            // Permission denied
            return response()->json(['success' => false]);
        }
    }


    public function fetchRoleId()
    {
        try {
            $role = Role::where('role', 'User')->first();

            if ($role) {
                return response()->json(['success' => true, 'roleId' => $role->id]);
            } else {
                return response()->json(['success' => false, 'message' => 'Role not found']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
