<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'username' => $credentials['username'],
            'password' => $credentials['password'],
            'status' => 'aktif',
        ])) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect('/admin');
            }

            if ($user->role === 'owner') {
                return redirect('/owner');
            }

            if ($user->role === 'pelanggan') {
                return redirect('/pelanggan');
            }
        }

        return back()
            ->withInput()
            ->with('error', 'Username atau password salah atau akun tidak aktif.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}