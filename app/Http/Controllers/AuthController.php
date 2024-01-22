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
     * The function "showLoginForm" returns a view for the login form.
     * 
     * @return a view called 'landing.login'.
     */
    public function showLoginForm()
    {
        return view('landing.login');
    }

    
    /**
     * The login function validates user input, attempts to log in the user using the provided
     * credentials, and redirects the user based on their role or displays an error message if
     * authentication fails.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains information about the request such as the request
     * method, URL, headers, and any data sent with the request.
     * 
     * @return a redirect response. If the user input fails validation, it redirects back to the login
     * page with the validation errors and the user's input. If the authentication attempt fails, it
     * redirects back to the login page with an error message indicating invalid credentials.
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
     * The function returns a view for the registration form.
     * 
     * @return a view called 'landing.register'.
     */
    public function showRegisterForm()
    {
        return view('landing.register');
    }

    
    /**
     * The function registers a new user by validating their input, handling profile image upload,
     * creating a new user record in the database, and logging in the user.
     * 
     * @param Request request The  parameter is an instance of the Request class, which
     * represents an HTTP request. It contains all the data and information about the current request,
     * such as the request method, URL, headers, and request payload.
     * 
     * @return a redirect response to the root URL ("/") after the user registration is successful.
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

        return redirect('/');
    }

    
    /**
     * The above function logs out the user and redirects them to the login page.
     * 
     * @return a redirect to the 'login' route.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    
    /**
     * The function redirects the user based on their role, either to the admin index page or the
     * default index page.
     * 
     * @param User user The "user" parameter is an instance of the User class. It represents the
     * currently logged-in user and contains information about their role or permissions.
     * 
     * @return a redirect response based on the role of the user. If the user is an admin, it will
     * redirect to the 'admin.index' route. If the user is a customer, it will redirect to the 'index'
     * route. If the user role is not recognized, it will redirect to the root URL '/'.
     */
    private function redirectBasedOnRole(User $user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        } elseif ($user->isCustomer()) {
            return redirect()->route('property_list');
        }

        // Default redirect if the user role is not recognized
        return redirect('/properties');
    }
}
