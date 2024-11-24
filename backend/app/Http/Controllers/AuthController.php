<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService,
        private AuthRepositoryInterface $authRepository,
    ) {}


    // Show the registration form
    public function showRegistrationForm()
    {
        try {
            if (Auth::guard('web')->check()) {
                return redirect()->route('auth.dashboard');
            }

            return view('auth.register');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to show registration form. ' . $e->getMessage()]);
        }
    }


    // Handle the registration
    public function register(RegisterRequest $request)
    {
        try {
            // Validate the request and get the validated data
            $validatedData = $request->validated();

            // Register the user and assign default role
            $response = $this->authRepository->register($validatedData, $request);

            // Log the user in
            Auth::login($response['data']);

            // Redirect to the dashboard
            return redirect()->route('auth.dashboard')->with('success', $response['message']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Registration failed. ' . $e->getMessage()]);
        }
    }


    // Show the login form
    public function showLoginForm()
    {
        try {
            if (Auth::guard('web')->check()) {
                return redirect()->route('auth.dashboard');
            }

            return view('auth.login');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to show login form. ' . $e->getMessage()]);
        }
    }


    // Handle the login
    public function login(LoginRequest $request)
    {
        try {
            $response = $this->authRepository->login($request);

            if ($response['status'] === 200) {
                return redirect()->intended(route('auth.dashboard'))->with('success', $response['message']);
            }
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            return back()->withErrors(['error' => 'Login failed. ' . $e->getMessage()]);
        }
    }


    // Handle the logout
    public function logout(Request $request)
    {
        try {
            // Logout and remove the session
            $response = $this->authRepository->logout($request);

            // Redirect to the login page
            return redirect()->route('auth.showLoginForm')->with('success', $response['message']);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to logout. ' . $e->getMessage()]);
        }
    }


    // Handle the home (/) URL
    public function home()
    {
        try {
            if (Auth::check()) {
                return redirect()->route('auth.dashboard');
            } else {
                return redirect()->route('auth.showLoginForm');
            }
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    // Handle the dashboard URL
    public function dashboard()
    {
        try {
            if (Auth::check()) {
                return view('dashboard');
            }

            return redirect()->route('auth.showLoginForm');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Unable to show dashboard. ' .  $e->getMessage()]);
        }
    }
}
