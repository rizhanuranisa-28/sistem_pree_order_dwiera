<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        return view('profil', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_users' => 'required|string|max:100',
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('users', 'username')
                    ->ignore($user->id_users, 'id_users'),
            ],
        ], [
            'nama_users.required' => 'Nama lengkap wajib diisi.',
            'nama_users.max' => 'Nama maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.unique' => 'Username sudah digunakan pengguna lain.',
        ]);

        $user->nama_users = $validated['nama_users'];
        $user->username = $validated['username'];
        $user->save();

        return redirect()
            ->route('profil')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}