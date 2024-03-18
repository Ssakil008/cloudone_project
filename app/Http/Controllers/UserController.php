<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entry;
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
        $request->validate([
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|numeric|min:11',
            'password' => 'required|string|min:8',
        ]);

        // $user = User::create([
        //     'credential_for' => $request->credential_for,
        //     'email' => $request->email,
        //     'url' => $request->url,
        //     'ip_address' => $request->ip_address,
        //     'username' => $request->username,
        //     'password' => Hash::make($request->password),
        // ]);

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
        return view('dashboard');
    }

    public function userProfile()
    {
        return view('user_profile');
    }

    public function newUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'credential_for' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|numeric|min:11',
            'url' => 'required|string|url',
            'ip_address' => 'required|ip',
            'username' => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }


        $id = $request->input('entryId');

        if (empty($id)) {
            $entry = new Entry();
            $entry->credential_for = $request->credential_for;
            $entry->email = $request->email;
            $entry->mobile = $request->mobile;
            $entry->url = $request->url;
            $entry->ip_address = $request->ip_address;
            $entry->username = $request->username;
            $entry->password = $request->password;
            $entryResult = $entry->save();
        } else {
            $entry = Entry::find($id);
            $entry->credential_for = $request->credential_for;
            $entry->email = $request->email;
            $entry->mobile = $request->mobile;
            $entry->url = $request->url;
            $entry->ip_address = $request->ip_address;
            $entry->username = $request->username;
            $entry->password = $request->password;
            $entryResult = $entry->save();
        }

        return response()->json(['success' => true]);
    }


    public function getEntries()
    {
        $entries = Entry::all();

        return response()->json(['data' => $entries]);
    }

    public function getEntry($id)
    {
        $entry = Entry::find($id);

        if ($entry) {
            return response()->json(['data' => $entry]);
        } else {
            return response()->json(['error' => 'Entry not found'], 404);
        }
    }

    public function deleteEntry(Request $request)
    {
        $Id = $request->input('entryId');


        // Validate that the entry and user exist
        $entry = Entry::find($Id);
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

    public function logout()
    {
        Session::flush();
        return response()->json(['success' => true]);
    }
}
