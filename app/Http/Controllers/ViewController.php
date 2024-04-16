<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\Menu;

class ViewController extends Controller
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

    public function dashboard()
    {
        return view('pages.dashboard');
    }

    public function credentialForServer()
    {
        return $this->checkPermission('credential_for_server', 'pages.credential_for_server');
    }

    public function credentialForUser()
    {
        return $this->checkPermission('credential_for_user', 'pages.credential_for_user');
    }

    public function userSetup()
    {
        return $this->checkPermission('user_setup', 'pages.user_setup');
    }

    public function role()
    {
        return $this->checkPermission('role', 'pages.role');
    }

    public function additional_information($id)
    {
        return view('subPages.additional_information', compact('id'));
    }


    public function checkPermission($menuLink, $viewName)
    {
        // Get the currently authenticated user's ID
        $userId = Auth::id();

        // Check if the user exists in the user_role table and get the role_id
        $roleId = DB::table('user_role')->where('user_id', $userId)->value('role_id');

        if ($roleId) {
            // If the role_id exists, proceed to check permissions
            // Find the menu item with the given link
            $menu = Menu::where('link', $menuLink)->first();

            if ($menu) {
                // If the menu exists, get its ID
                $menuIdToCheck = $menu->id;

                // Check if a permission exists for the role and the specified menu ID
                $permissionExists = Permission::where('role_id', $roleId)
                    ->where('menu_id', $menuIdToCheck)
                    ->exists();

                if ($permissionExists) {
                    // If the permission exists for the role and the menu ID, you can proceed
                    return view($viewName, ['menuId' => $menu->id]);
                } else {
                    return redirect()->intended(route('dashboard'));
                }
            } else {
                return redirect()->intended(route('dashboard'));
            }
        } else {
            return redirect()->intended(route('dashboard'));
        }
    }
}