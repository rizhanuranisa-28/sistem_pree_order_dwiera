<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class StrukController extends Controller
{
    // Struk hanya dapat dicetak oleh admin setelah pesanan selesai.
    public function admin($id)
    {
        abort_unless(
            Auth::user()->role === 'admin',
            403,
            'Halaman ini hanya dapat diakses admin.'
        );

        $pesanan = Pesanan::with([
            'user',
            'detailPesanans.produk',
            'pembayaran',
            'jadwalPengambilan',
        ])->findOrFail($id);

        // Struk hanya tersedia jika pesanan sudah selesai.
        if (strtolower(trim($pesanan->status_pesanan)) !== 'selesai') {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Struk hanya dapat dicetak setelah pesanan selesai.'
                );
        }

        return view('struk', compact('pesanan'));
    }

    // Pelanggan hanya dapat melihat status pesanan.
    public function pelanggan($id)
    {
        return redirect()->route('pesanan.pelanggan');
    }
}