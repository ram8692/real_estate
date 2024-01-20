<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function profile()
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Pass the user information to the view
        return view('landing.profile', compact('user'));
    }
}
