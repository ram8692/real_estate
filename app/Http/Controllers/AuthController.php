<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Validators\AuthValidators;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  public function showLoginForm()
  {
    return view('landing.login');
  }

  public function login(Request $request)
  {
    // print_r($request->all());die();
    $validator = AuthValidators::validate('login', $request->all());

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      return redirect()->route('login')->withErrors($errors)->withInput();
    }

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
      $user = Auth::user();
      // Redirect based on user role
      if ($user->isAdmin()) {
        return redirect()->route('admin.index');
      } elseif ($user->isCustomer()) {
        return redirect()->route('index');
      }
    }

    // Authentication failed
    return redirect()->route('login')->withErrors(['email' => 'Invalid credentials']);
  }

  public function showRegisterForm()
  {
    return view('landing.register');
  }

  public function register(Request $request)
  {

    $validator = AuthValidators::validate('register', $request->all());

    if ($validator->fails()) {
      $errors = $validator->errors()->toArray();
      return redirect()->route('register')->withErrors($errors)->withInput();
    }

    // Handle profile image upload
    $profileImage = $request->file('profile_image');
    $profileImageName = null;

    if ($profileImage) {
      $uniqueFileName = Str::uuid() . '.' . $profileImage->getClientOriginalExtension();
      $profileImage->storeAs('assets/profile_images', $uniqueFileName, 'public');
      $profileImageName = $uniqueFileName;

    }

    // Create a new user
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => bcrypt($request->password),
      'role_id' => 2,
      'profile_image' => $profileImageName,
    ]);

    // Log in the user
    Auth::login($user);

    return redirect('/'); // Redirect to a dashboard or another route after registration
  }

  public function logout()
  {
    Auth::logout();
    return redirect()->route('login');
  }
}
