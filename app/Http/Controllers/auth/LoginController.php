<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => "required",
            'password' => "required",
        ]);

        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            return redirect('/dashboard');
        } else {
            return redirect()->back()->withErrors(['name' => 'Invalid username or password']);
        }
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
