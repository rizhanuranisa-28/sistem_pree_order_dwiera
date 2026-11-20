<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

class StrukController extends Controller
{
    // Struk cash dapat dicetak sebelum pesanan selesai; transfer dan QRIS menunggu pesanan selesai.
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
            'customCakeOrder',
            'pembayaran',
            'jadwalPengambilan',
        ])->findOrFail($id);

        $metodePembayaran = strtolower(trim(
            (string) ($pesanan->pembayaran->metode_pembayaran ?? '')
        ));
        $metodeCash = in_array($metodePembayaran, ['cash', 'tunai'], true);
        $pesananSelesai = strtolower(trim(
            (string) $pesanan->status_pesanan
        )) === 'selesai';

        if (!$metodeCash && !$pesananSelesai) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Struk pembayaran transfer atau QRIS hanya dapat dilihat setelah pesanan selesai.'
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