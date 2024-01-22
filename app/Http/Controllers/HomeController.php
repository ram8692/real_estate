<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * The index function returns the view for the landing page.
     * 
     * @return The view 'landing.index' is being returned.
     */
    public function index()
    {
        return view('landing.index');
    }
}
