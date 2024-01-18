<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('',[HomeController::class,'index'])->name('users.index');;
Route::get('profile',[UsersController::class,'profile'])->name('profile');;
Route::get('login',[LoginController::class,'showLoginForm'])->name('login');;
Route::get('register',[RegisterController::class,'showRegisterForm'])->name('register');;
Route::get('properties',[PropertiesController::class,'properties_list'])->name('property_list');;
Route::get('property',[PropertiesController::class,'property'])->name('property');;