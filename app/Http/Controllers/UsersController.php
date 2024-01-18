<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function profile(){
       // dd(33);
        return view('landing.profile');
    }
}
