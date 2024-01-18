<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PropertiesController extends Controller
{
    public function properties_list(){
        return view("landing.property_list");
    }

    public function property(){
        return view("landing.property");
    }
}
