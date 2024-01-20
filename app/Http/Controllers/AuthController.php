<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Validators\AuthValidators;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('landing.login');
    }

    /**
     * Handle user login.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // Validate user input
        $validator = AuthValidators::validate('login', $request->all());

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            return redirect()->route('login')->withErrors($errors)->withInput();
        }

        // Attempt to log in the user
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Redirect based on user role
            return $this->redirectBasedOnRole($user);
        }

        // Authentication failed
        return redirect()->route('login')->withErrors(['email' => 'Invalid credentials']);
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('landing.register');
    }

    /**
     * Handle user registration.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate user input
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

    /**
     * Log out the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Redirect the user based on their role.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        } elseif ($user->isCustomer()) {
            return redirect()->route('index');
        }

        // Default redirect if the user role is not recognized
        return redirect('/');
    }
}
