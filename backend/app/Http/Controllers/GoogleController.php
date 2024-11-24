<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\GoogleService;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    public function __construct(private GoogleService $googleService) {}

    public function redirectToGoogle(Request $request)
    {
        try {
            return $this->googleService->redirectToGoogle($request);
        } catch (Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'Redirect to Google failed. ' . $e->getMessage()]);
        }
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Register the user and assign default role
            $response = $this->googleService->handleGoogleCallback($request);

            // Redirect to the dashboard
            return redirect()->route('auth.dashboard')->with('success', $response['message']);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Google callback failed! ' . $e->getMessage()]);
        }
    }
}
