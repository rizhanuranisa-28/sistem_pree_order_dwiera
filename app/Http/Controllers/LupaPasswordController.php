<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LupaPasswordController extends Controller
{
    public function form()
    {
        return view('lupa-password');
    }

    public function kirimLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam akun Dwiera.',
            ])->withInput();
        }

        $token = Str::random(64);

        session([
            'reset_password_user_id' => $user->id_users,
            'reset_password_token' => $token,
            'reset_password_expired' => now()->addMinutes(10)->timestamp,
        ]);

        return redirect()->route('password.reset', [
            'token' => $token,
        ])->with('success', 'Silakan buat password baru.');
    }

    public function formReset($token)
    {
        if (!$this->tokenValid($token)) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'Permintaan reset password tidak valid atau sudah kedaluwarsa.',
                ]);
        }

        return view('reset-password', [
            'token' => $token,
        ]);
    }

    public function simpanPassword(Request $request, $token)
    {
        if (!$this->tokenValid($token)) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'Permintaan reset password tidak valid atau sudah kedaluwarsa.',
                ]);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::find(session('reset_password_user_id'));

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors([
                    'email' => 'Akun tidak ditemukan.',
                ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget([
            'reset_password_user_id',
            'reset_password_token',
            'reset_password_expired',
        ]);

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah. Silakan login.');
    }

    private function tokenValid($token): bool
    {
        return session()->has('reset_password_token')
            && hash_equals(session('reset_password_token'), (string) $token)
            && session('reset_password_expired', 0) >= now()->timestamp;
    }
}