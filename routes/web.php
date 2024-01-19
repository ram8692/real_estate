<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\AuthController;


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


Route::get('', [HomeController::class, 'index'])->name('index');
Route::get('profile', [UsersController::class, 'profile'])->name('profile');
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('properties', [PropertiesController::class, 'properties_list'])->name('property_list');
Route::get('/property/{id}/info', [PropertiesController::class, 'property'])->name('property.info');

Route::get('admin_panel', [AdminController::class, 'index'])->name('admin.index');
Route::get('property/list', [PropertiesController::class, 'index'])->name('property.list');
Route::get('property/create', [PropertiesController::class, 'create'])->name('property.create');

Route::post('property/save', [PropertiesController::class, 'store'])->name('property.store');

// Show form to edit an existing user
Route::get('/property/{id}/edit', [PropertiesController::class, 'edit'])->name('property.edit');

// Update an existing user in the database
Route::put('/property/{id}/update', [PropertiesController::class, 'update'])->name('property.update');

// Delete a user
Route::delete('/property/{id}/delete', [PropertiesController::class, 'destroy'])->name('property.destroy');



// List galleries for a specific property
Route::get('/galleries/{property_id}', [GalleryController::class, 'index'])->name('galleries.index');

// Delete a gallery
Route::delete('/galleries/{id}/delete', [GalleryController::class, 'destroy'])->name('gallery.destroy');

Route::get('/messages/{property_id}', [MessagesController::class, 'index'])->name('messages.index');
Route::get('/messages/{id}/responsd', [MessagesController::class, 'respond'])->name('message.respond');
Route::post('message/{id}/reply', [MessagesController::class, 'reply'])->name('message.reply');