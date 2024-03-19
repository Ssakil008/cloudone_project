<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CredentialForServer;
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
        $rules = [
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|string|min:11',
        ];

        // Include password validation only for new users (not for updates)
        if (!$request->has('id')) {
            $rules['password'] = 'required|string|min:8';
        }

        $request->validate($rules);

        $userData = [
            'email' => $request->email,
            'mobile' => $request->mobile,
        ];

        // Check if ID is provided
        if ($request->has('id')) {
            // Update user if ID exists
            $user = User::find($request->id);
            if ($user) {
                $user->update($userData);
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'fail' => 'User not found'], 404);
            }
        } else {
            // Insert new user if no ID provided
            $user = new User();
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password);
            $result = $user->save();

            if ($result) {
                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'fail' => 'Something went wrong']);
            }
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

    public function newUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credential_for' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
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


    public function getEntries()
    {
        $entries = CredentialForServer::all();

        return response()->json(['data' => $entries]);
    }

    public function getAllUserData()
    {
        $users = User::all();

        return response()->json(['data' => $users]);
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

    public function deleteEntry(Request $request)
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

    public function logout()
    {
        Session::flush();
        return response()->json(['success' => true]);
    }

    public function userSetup()
    {
        return view('pages.user_setup');
    }
}
