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

//for landing page
Route::get('', [HomeController::class, 'index'])->name('index');

//show form of login page
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');

//show form of register page
Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');

//for login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

//for register
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

//for properties list page
Route::get('properties', [PropertiesController::class, 'properties_list'])->name('property_list');

//for property info page
Route::get('/property/{id}/info', [PropertiesController::class, 'property'])->name('property.info');

//logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

//ROUTES FOR ADMIN
Route::middleware(['role:1'])->prefix('admin_panel')->group(function () {

    // List galleries for a specific property
    Route::get('/galleries/{property_id}', [GalleryController::class, 'index'])->name('galleries.index');

    // Delete a gallery
    Route::delete('/galleries/{id}/delete', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    // Show form to edit an existing property
    Route::get('/messages/{property_id}', [MessagesController::class, 'index'])->name('messages.index');

    // Show form for message to respond
    Route::get('/messages/{id}/respond', [MessagesController::class, 'respond'])->name('message.respond');

    // Reply to a message
    Route::post('message/{id}/reply', [MessagesController::class, 'reply'])->name('message.reply');

    //for admin dashboard
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    //Show list of properties
    Route::get('property/list', [PropertiesController::class, 'index'])->name('property.list');

    // Show form to create a new property
    Route::get('property/create', [PropertiesController::class, 'create'])->name('property.create');

    // Store a new property in the database
    Route::post('property/save', [PropertiesController::class, 'store'])->name('property.store');

    // Show form to edit an existing user
    Route::get('/property/{id}/edit', [PropertiesController::class, 'edit'])->name('property.edit');

    // Update an existing user in the database
    Route::put('/property/{id}/update', [PropertiesController::class, 'update'])->name('property.update');

    // Delete a user
    Route::delete('/property/{id}/delete', [PropertiesController::class, 'destroy'])->name('property.destroy');

});

//ROUTES FOR CUSTOMER
Route::middleware(['role:2'])->group(function () {

    //profile page of user
    Route::get('profile', [UsersController::class, 'profile'])->name('profile');

    //for Sending Messages
    Route::post('message/send', [MessagesController::class, 'send'])->name('message.send');
});