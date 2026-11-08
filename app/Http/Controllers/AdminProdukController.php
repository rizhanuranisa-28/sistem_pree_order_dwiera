<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\UkuranProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdminProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::with('ukuranProduks')
            ->orderBy('id_produk', 'desc')
            ->get();

        return view('admin_produk', compact('produk'));
    }

    public function create()
    {
        return view('admin_produk_tambah');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kue' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',

            'ukuran_produk' => 'required|array|min:1',
            'ukuran_produk.*.nama_ukuran' => 'required|string|max:50|distinct',
            'ukuran_produk.*.harga' => 'required|numeric|min:0',
            'ukuran_produk.*.status' => 'nullable|in:aktif,nonaktif',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_kue.required' => 'Nama kue wajib diisi.',
            'ukuran_produk.required' => 'Minimal harus ada satu ukuran.',
            'ukuran_produk.min' => 'Minimal harus ada satu ukuran.',
            'ukuran_produk.*.nama_ukuran.required' => 'Nama ukuran wajib diisi.',
            'ukuran_produk.*.nama_ukuran.distinct' => 'Nama ukuran tidak boleh sama.',
            'ukuran_produk.*.harga.required' => 'Harga setiap ukuran wajib diisi.',
            'ukuran_produk.*.harga.numeric' => 'Harga harus berupa angka.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $namaGambar = null;

        if ($request->hasFile('gambar')) {
            $folder = public_path('images/produk');

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $file = $request->file('gambar');

            $namaGambar = time() . '_' . uniqid() . '.' .
                $file->getClientOriginalExtension();

            $file->move($folder, $namaGambar);
        }

        $daftarUkuran = array_values($validated['ukuran_produk']);
        $ukuranPertama = $daftarUkuran[0];

        DB::transaction(function () use (
            $validated,
            $namaGambar,
            $daftarUkuran,
            $ukuranPertama
        ) {
            $produk = Produk::create([
                'nama_kue' => $validated['nama_kue'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga' => $ukuranPertama['harga'],
                'ukuran' => $ukuranPertama['nama_ukuran'],
                'gambar' => $namaGambar,
                'status' => $validated['status'],
            ]);

            foreach ($daftarUkuran as $ukuran) {
                $produk->ukuranProduks()->create([
                    'nama_ukuran' => $ukuran['nama_ukuran'],
                    'harga' => $ukuran['harga'],
                    'status' => $ukuran['status'] ?? 'aktif',
                ]);
            }
        });

        return redirect()
            ->route('admin.produk')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $produk = Produk::with('ukuranProduks')
            ->findOrFail($id);

        return view('admin_produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::with('ukuranProduks')
            ->findOrFail($id);

        $validated = $request->validate([
            'nama_kue' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',

            'ukuran_produk' => 'required|array|min:1',
            'ukuran_produk.*.id_ukuran' => 'nullable|integer',
            'ukuran_produk.*.nama_ukuran' => 'required|string|max:50|distinct',
            'ukuran_produk.*.harga' => 'required|numeric|min:0',
            'ukuran_produk.*.status' => 'required|in:aktif,nonaktif',

            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:aktif,nonaktif',
        ], [
            'nama_kue.required' => 'Nama kue wajib diisi.',
            'ukuran_produk.required' => 'Minimal harus ada satu ukuran.',
            'ukuran_produk.min' => 'Minimal harus ada satu ukuran.',
            'ukuran_produk.*.nama_ukuran.required' => 'Nama ukuran wajib diisi.',
            'ukuran_produk.*.nama_ukuran.distinct' => 'Nama ukuran tidak boleh sama.',
            'ukuran_produk.*.harga.required' => 'Harga setiap ukuran wajib diisi.',
            'ukuran_produk.*.harga.numeric' => 'Harga harus berupa angka.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, JPEG, PNG, atau WEBP.',
            'gambar.max' => 'Ukuran gambar maksimal 2 MB.',
        ]);

        $daftarUkuran = array_values($validated['ukuran_produk']);
        $ukuranPertama = $daftarUkuran[0];

        $namaGambar = $produk->gambar;
        $gambarLama = $produk->gambar;

        if ($request->hasFile('gambar')) {
            $folder = public_path('images/produk');

            if (!File::exists($folder)) {
                File::makeDirectory($folder, 0755, true);
            }

            $file = $request->file('gambar');

            $namaGambar = time() . '_' . uniqid() . '.' .
                $file->getClientOriginalExtension();

            $file->move($folder, $namaGambar);
        }

        DB::transaction(function () use (
            $produk,
            $validated,
            $daftarUkuran,
            $ukuranPertama,
            $namaGambar
        ) {
            $produk->update([
                'nama_kue' => $validated['nama_kue'],
                'deskripsi' => $validated['deskripsi'] ?? null,
                'harga' => $ukuranPertama['harga'],
                'ukuran' => $ukuranPertama['nama_ukuran'],
                'gambar' => $namaGambar,
                'status' => $validated['status'],
            ]);

            foreach ($daftarUkuran as $ukuran) {
                if (!empty($ukuran['id_ukuran'])) {
                    $dataUkuran = UkuranProduk::where(
                        'id_produk',
                        $produk->id_produk
                    )->find($ukuran['id_ukuran']);

                    if (!$dataUkuran) {
                        throw ValidationException::withMessages([
                            'ukuran_produk' => 'Data ukuran tidak sesuai dengan produk.',
                        ]);
                    }

                    $dataUkuran->update([
                        'nama_ukuran' => $ukuran['nama_ukuran'],
                        'harga' => $ukuran['harga'],
                        'status' => $ukuran['status'],
                    ]);
                } else {
                    $produk->ukuranProduks()->create([
                        'nama_ukuran' => $ukuran['nama_ukuran'],
                        'harga' => $ukuran['harga'],
                        'status' => $ukuran['status'],
                    ]);
                }
            }
        });

        // Hapus gambar lama setelah data berhasil diperbarui.
        if (
            $request->hasFile('gambar') &&
            $gambarLama &&
            File::exists(public_path('images/produk/' . $gambarLama))
        ) {
            File::delete(public_path('images/produk/' . $gambarLama));
        }

        return redirect()
            ->route('admin.produk')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        // Produk tidak boleh dihapus jika sudah digunakan dalam pesanan.
        if ($produk->detailPesanans()->exists()) {
            return redirect()
                ->route('admin.produk')
                ->with(
                    'error',
                    'Produk tidak dapat dihapus karena sudah digunakan dalam pesanan.'
                );
        }

        // Hapus file gambar produk jika ada.
        if (
            $produk->gambar &&
            File::exists(public_path('images/produk/' . $produk->gambar))
        ) {
            File::delete(
                public_path('images/produk/' . $produk->gambar)
            );
        }

        // Data ukuran ikut terhapus melalui foreign key cascade.
        $produk->delete();

        return redirect()
            ->route('admin.produk')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
