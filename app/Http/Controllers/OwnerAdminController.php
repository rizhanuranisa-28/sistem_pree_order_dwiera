<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class OwnerAdminController extends Controller
{
    /**
     * Daftar akun admin + form tambah admin.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->orderBy('nama_users')
            ->get();

        return view('owner_admin', compact('admins'));
    }

    /**
     * Owner membuat akun admin baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_users' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('users', 'email'),
            ],
            'username' => 'required|string|max:50|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nama_users.required' => 'Nama lengkap wajib diisi.',
            'nomor_telepon.required' => 'Nomor telepon wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        User::create([
            'nama_users' => $request->nama_users,
            'nomor_telepon' => $request->nomor_telepon,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'status' => 'aktif',
        ]);

        return redirect()
            ->route('owner.admin')
            ->with('success', 'Akun admin berhasil dibuat.');
    }

    /**
     * Owner mengganti password akun admin.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $admin = User::where('id_users', $id)
            ->where('role', 'admin')
            ->firstOrFail();

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()
            ->route('owner.admin')
            ->with('success', 'Password admin ' . $admin->nama_users . ' berhasil diganti.');
    }

    /**
     * Owner mengaktifkan atau menonaktifkan akun admin.
     */
    public function toggleStatus($id)
    {
        $admin = User::where('id_users', $id)
            ->where('role', 'admin')
            ->firstOrFail();

        $admin->status = ($admin->status ?? 'aktif') === 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $admin->save();

        $pesan = $admin->status === 'aktif'
            ? 'berhasil diaktifkan.'
            : 'berhasil dinonaktifkan.';

        return redirect()
            ->route('owner.admin')
            ->with('success', 'Akun admin ' . $admin->nama_users . ' ' . $pesan);
    }
}