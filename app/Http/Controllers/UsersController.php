<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;


use Illuminate\Http\Request;

class UsersController extends Controller
{
    /**
     * The profile function retrieves the currently authenticated user and passes their information to
     * the landing.profile view.
     * 
     * @return a view called 'landing.profile' and passing the authenticated user's information as a
     * variable named 'user' to the view.
     */
    public function profile()
    {
        // Retrieve the currently authenticated user
        $user = Auth::user();

        // Pass the user information to the view
        return view('landing.profile', compact('user'));
    }
}
