<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/* The AdminController class extends the Controller class and has an index method that returns the
admin.index view. */
class AdminController extends Controller
{
    public function index(){
        return view("admin.index");
    }
}
