<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // CLIENT register
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'employee_id'  => 'required|string|unique:users',
            'dob'          => 'required|date',
            'address'      => 'required|string|max:500',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'        => $data['name'],
            'employee_id' => $data['employee_id'],
            'dob'         => $data['dob'],
            'address'     => $data['address'],
            'email'       => $data['email'],
            'password'    => Hash::make($data['password']),
            'role'        => 'client',
        ]);

        Auth::login($user);
        return redirect()->route('client.home');
    }

    // CLIENT login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'client']))) {
            $request->session()->regenerate();
            return redirect()->route('client.home');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // BOOKKEEPER login
    public function loginBookkeeper(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'bookkeeper']))) {
            $request->session()->regenerate();
            return redirect()->route('bookkeeper.home');
        }

        return back()->withErrors(['email' => 'Invalid bookkeeper credentials']);
    }

    // ADMIN login
    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admin.home');
        }

        return back()->withErrors(['email' => 'Invalid admin credentials']);
    }

    // Logout (all roles)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('dashboard');
    }
}
