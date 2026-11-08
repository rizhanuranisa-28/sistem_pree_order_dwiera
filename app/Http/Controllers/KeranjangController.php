<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\UkuranProduk;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    /**
     * Menampilkan keranjang pelanggan.
     */
    public function index()
    {
        $keranjang = session()->get('keranjang', []);

        $items = [];
        $total = 0;

        foreach ($keranjang as $key => $data) {
            $produk = Produk::where('status', 'aktif')
                ->find($data['id_produk'] ?? null);

            $ukuran = UkuranProduk::where('id_produk', $data['id_produk'] ?? null)
                ->where('status', 'aktif')
                ->find($data['id_ukuran'] ?? null);

            if (!$produk || !$ukuran) {
                continue;
            }

            $jumlah = (int) ($data['jumlah'] ?? 1);
            $jumlah = max(1, min(100, $jumlah));

            $harga = (float) $ukuran->harga;
            $subtotal = $harga * $jumlah;

            $items[] = [
                'key' => $key,
                'produk' => $produk,
                'ukuran' => $ukuran,
                'jumlah' => $jumlah,
                'harga' => $harga,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        return view('keranjang', compact('items', 'total'));
    }

    /**
     * Menambahkan produk ke keranjang.
     */
    public function tambah(Request $request, $id)
    {
        $validated = $request->validate([
            'id_ukuran' => ['required', 'integer'],
            'jumlah' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Cari produk yang masih aktif.
        $produk = Produk::where('status', 'aktif')
            ->find($id);

        if (!$produk) {
            return redirect()
                ->back()
                ->with('error', 'Produk tidak ditemukan atau sudah tidak tersedia.');
        }

        // Cari ukuran yang sesuai dengan produk dan masih aktif.
        $ukuran = UkuranProduk::where('id_ukuran', $validated['id_ukuran'])
            ->where('id_produk', $produk->id_produk)
            ->where('status', 'aktif')
            ->first();

        if (!$ukuran) {
            return redirect()
                ->back()
                ->with('error', 'Ukuran kue tidak tersedia. Silakan pilih ukuran lain.');
        }

        // Ambil jumlah yang dikirim dari form.
        $jumlah = (int) ($validated['jumlah'] ?? 1);
        $jumlah = max(1, min(100, $jumlah));

        // Ambil data keranjang dari session.
        $keranjang = session()->get('keranjang', []);

        // Buat key berdasarkan ID produk dan ID ukuran.
        $key = $produk->id_produk . '_' . $ukuran->id_ukuran;

        // Jika produk dengan ukuran yang sama sudah ada, tambahkan jumlahnya.
        if (isset($keranjang[$key])) {
            $jumlahLama = (int) ($keranjang[$key]['jumlah'] ?? 1);

            if (($jumlahLama + $jumlah) > 100) {
                return redirect()
                    ->route('keranjang.index')
                    ->with('error', 'Jumlah maksimal satu ukuran kue adalah 100.');
            }

            $keranjang[$key]['jumlah'] = $jumlahLama + $jumlah;
        } else {
            // Jika belum ada, masukkan sebagai produk baru.
            $keranjang[$key] = [
                'id_produk' => $produk->id_produk,
                'id_ukuran' => $ukuran->id_ukuran,
                'jumlah' => $jumlah,
            ];
        }

        // Simpan keranjang ke session.
        session()->put('keranjang', $keranjang);
        session()->save();

        return redirect()
            ->route('keranjang.index')
            ->with(
                'success',
                $produk->nama_kue . ' ukuran ' .
                $ukuran->nama_ukuran .
                ' berhasil ditambahkan ke keranjang.'
            );
    }

    /**
     * Menambah atau mengurangi jumlah produk.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'aksi' => ['required', 'in:tambah,kurang'],
        ]);

        $keranjang = session()->get('keranjang', []);

        if (!isset($keranjang[$id])) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        $data = $keranjang[$id];

        $produk = Produk::where('status', 'aktif')
            ->find($data['id_produk'] ?? null);

        $ukuran = UkuranProduk::where('id_ukuran', $data['id_ukuran'] ?? null)
            ->where('id_produk', $data['id_produk'] ?? null)
            ->where('status', 'aktif')
            ->first();

        if (!$produk || !$ukuran) {
            unset($keranjang[$id]);

            session()->put('keranjang', $keranjang);
            session()->save();

            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Produk atau ukuran sudah tidak tersedia.');
        }

        $jumlah = (int) ($data['jumlah'] ?? 1);

        if ($validated['aksi'] === 'tambah') {
            if ($jumlah >= 100) {
                return back()->with(
                    'error',
                    'Jumlah maksimal satu ukuran kue adalah 100.'
                );
            }

            $jumlah++;
        } else {
            if ($jumlah <= 1) {
                return back()->with(
                    'error',
                    'Jumlah minimal satu. Gunakan tombol Hapus jika tidak jadi membeli.'
                );
            }

            $jumlah--;
        }

        $keranjang[$id]['jumlah'] = $jumlah;

        session()->put('keranjang', $keranjang);
        session()->save();

        return redirect()
            ->route('keranjang.index')
            ->with(
                'success',
                'Jumlah ' . $produk->nama_kue . ' ukuran ' .
                $ukuran->nama_ukuran . ' berhasil diperbarui.'
            );
    }

    /**
     * Menghapus satu produk dari keranjang.
     */
    public function hapus($id)
    {
        $keranjang = session()->get('keranjang', []);

        if (!isset($keranjang[$id])) {
            return redirect()
                ->route('keranjang.index')
                ->with('error', 'Produk tidak ditemukan di keranjang.');
        }

        unset($keranjang[$id]);

        session()->put('keranjang', $keranjang);
        session()->save();

        return redirect()
            ->route('keranjang.index')
            ->with('success', 'Produk berhasil dihapus dari keranjang.');
    }

    /**
     * Mengosongkan seluruh keranjang.
     */
    public function kosongkan()
    {
        session()->forget('keranjang');
        session()->save();

        return redirect()
            ->route('keranjang.index')
            ->with('success', 'Keranjang berhasil dikosongkan.');
    }
}