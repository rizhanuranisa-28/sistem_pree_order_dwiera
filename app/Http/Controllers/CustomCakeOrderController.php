<?php

namespace App\Http\Controllers;

use App\Models\CustomCakeBasePrice;
use App\Models\CustomCakeOrder;
use App\Support\NominalRupiah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class CustomCakeOrderController extends Controller
{
    public function create()
    {
        $hargaDasar = CustomCakeOrder::daftarHargaDasar();

        return view('custom_cake_create', compact('hargaDasar'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ukuran_cm' => [
                'required',
                Rule::in(array_map('strval', array_keys(CustomCakeOrder::daftarHargaDasar()))),
            ],
            'tema' => ['required', 'string', 'max:100'],
            'tulisan' => ['required', 'string', 'max:100'],
            'dekorasi' => ['required', 'string', 'max:2000'],
            'foto_referensi' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'catatan_pelanggan' => ['nullable', 'string', 'max:1000'],
        ]);

        $foto = null;
        if ($request->hasFile('foto_referensi')) {
            $foto = $request->file('foto_referensi')
                ->store('custom-cake-references', 'local');

            if (! $foto) {
                throw new RuntimeException('Foto referensi gagal disimpan.');
            }
        }

        CustomCakeOrder::create([
            'id_users' => Auth::user()->id_users,
            'ukuran_cm' => $validated['ukuran_cm'],
            'harga_dasar' => CustomCakeOrder::hargaDasarUntukUkuran($validated['ukuran_cm']),
            'tema' => $validated['tema'],
            'tulisan' => $validated['tulisan'],
            'dekorasi' => $validated['dekorasi'],
            'foto_referensi' => $foto,
            'catatan_pelanggan' => $validated['catatan_pelanggan'] ?? null,
            'status' => CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI,
        ]);

        return redirect()
            ->route('custom-orders.index')
            ->with('success', 'Permintaan kue custom berhasil dikirim dan menunggu konfirmasi admin.');
    }

    public function index()
    {
        $permintaan = CustomCakeOrder::where('id_users', Auth::user()->id_users)
            ->with('pesanan.pembayaran')
            ->latest('id_custom_order')
            ->get();

        return view('custom_cake_history', compact('permintaan'));
    }

    public function reference($id)
    {
        $permintaan = CustomCakeOrder::findOrFail($id);
        $user = Auth::user();

        abort_unless(
            $user->role === 'admin' ||
                (int) $permintaan->id_users === (int) $user->id_users,
            403
        );

        abort_unless(
            $permintaan->foto_referensi &&
                Storage::disk('local')->exists($permintaan->foto_referensi),
            404
        );

        return Storage::disk('local')->response($permintaan->foto_referensi);
    }

    public function accept($id)
    {
        $permintaan = DB::transaction(function () use ($id) {
            $permintaan = CustomCakeOrder::where('id_custom_order', $id)
                ->where('id_users', Auth::user()->id_users)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $permintaan->status === CustomCakeOrder::STATUS_MENUNGGU_PERSETUJUAN &&
                    $permintaan->biaya_dekorasi !== null,
                403,
                'Penawaran harga belum dapat disetujui.'
            );

            $permintaan->update([
                'status' => CustomCakeOrder::STATUS_DISETUJUI,
            ]);

            return $permintaan;
        });

        return redirect()
            ->route('custom-orders.checkout', $permintaan->id_custom_order)
            ->with('success', 'Harga disetujui. Silakan lanjutkan checkout.');
    }

    public function cancel($id)
    {
        DB::transaction(function () use ($id) {
            $permintaan = CustomCakeOrder::where('id_custom_order', $id)
                ->where('id_users', Auth::user()->id_users)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                in_array($permintaan->status, [
                    CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI,
                    CustomCakeOrder::STATUS_MENUNGGU_PERSETUJUAN,
                    CustomCakeOrder::STATUS_DISETUJUI,
                ], true) && ! $permintaan->id_pesanan,
                403,
                'Permintaan yang sudah masuk pesanan tidak dapat dibatalkan dari halaman ini.'
            );

            $permintaan->update([
                'status' => CustomCakeOrder::STATUS_DIBATALKAN,
            ]);
        });

        return redirect()
            ->route('custom-orders.index')
            ->with('success', 'Permintaan kue custom berhasil dibatalkan.');
    }

    public function checkout($id)
    {
        $permintaan = CustomCakeOrder::where('id_custom_order', $id)
            ->where('id_users', Auth::user()->id_users)
            ->firstOrFail();

        abort_unless(
            $permintaan->status === CustomCakeOrder::STATUS_DISETUJUI &&
                $permintaan->biaya_dekorasi !== null &&
                ! $permintaan->id_pesanan,
            403,
            'Permintaan belum disetujui atau sudah masuk pesanan.'
        );

        session()->put('custom_cake_order_id', $permintaan->id_custom_order);

        return redirect()->route('checkout.keranjang');
    }

    public function adminIndex()
    {
        $permintaan = CustomCakeOrder::with('user')
            ->latest('id_custom_order')
            ->get();
        $hargaDasar = CustomCakeOrder::daftarHargaDasar();

        return view('custom_cake_admin_index', compact('permintaan', 'hargaDasar'));
    }

    public function updateBasePrices(Request $request)
    {
        $hargaDasarSaatIni = CustomCakeOrder::daftarHargaDasar();
        $hargaDasar = $request->input('harga_dasar');
        if (is_array($hargaDasar)) {
            foreach (array_keys($hargaDasarSaatIni) as $ukuran) {
                if (array_key_exists($ukuran, $hargaDasar)) {
                    $hargaDasar[$ukuran] = NominalRupiah::normalisasi(
                        $hargaDasar[$ukuran]
                    );
                }
            }
        }

        $ukuranBaru = $request->input('ukuran_baru', []);
        $hargaBaru = $request->input('harga_baru', []);
        if (is_array($hargaBaru)) {
            foreach ($hargaBaru as $index => $harga) {
                $hargaBaru[$index] = NominalRupiah::normalisasi($harga);
            }
        }

        $request->merge([
            'harga_dasar' => $hargaDasar,
            'ukuran_baru' => $ukuranBaru,
            'harga_baru' => $hargaBaru,
        ]);

        $rules = [
            'harga_dasar' => ['required', 'array', 'size:'.count($hargaDasarSaatIni)],
            'ukuran_baru' => ['nullable', 'array'],
            'ukuran_baru.*' => ['required', 'integer', 'min:1', 'max:500', 'distinct'],
            'harga_baru' => ['nullable', 'array'],
            'harga_baru.*' => ['required', 'integer', 'min:0', 'max:999999999'],
        ];
        foreach (array_keys($hargaDasarSaatIni) as $ukuran) {
            $rules["harga_dasar.$ukuran"] = [
                'required',
                'integer',
                'min:0',
                'max:999999999',
            ];
        }

        $validated = $request->validate($rules);

        $ukuranBaru = $validated['ukuran_baru'] ?? [];
        $hargaBaru = $validated['harga_baru'] ?? [];
        if (count($ukuranBaru) !== count($hargaBaru)) {
            throw ValidationException::withMessages([
                'harga_baru' => 'Masukkan harga untuk setiap ukuran baru.',
            ]);
        }

        $ukuranTerpakai = array_map('strval', array_keys($hargaDasarSaatIni));
        $ukuranTambahan = [];
        foreach ($ukuranBaru as $index => $ukuran) {
            $ukuran = (string) ((int) $ukuran);
            if (in_array($ukuran, $ukuranTerpakai, true)) {
                throw ValidationException::withMessages([
                    "ukuran_baru.$index" => 'Ukuran tersebut sudah tersedia.',
                ]);
            }

            $ukuranTerpakai[] = $ukuran;
            $ukuranTambahan[$index] = $ukuran;
        }

        DB::transaction(function () use ($validated, $ukuranTambahan) {
            foreach ($validated['harga_dasar'] as $ukuran => $harga) {
                CustomCakeBasePrice::updateOrCreate(
                    ['ukuran_cm' => (string) $ukuran],
                    ['harga_dasar' => (int) $harga]
                );
            }

            foreach ($ukuranTambahan as $index => $ukuran) {
                CustomCakeBasePrice::create([
                    'ukuran_cm' => $ukuran,
                    'harga_dasar' => (int) $validated['harga_baru'][$index],
                ]);
            }
        });

        return redirect()
            ->route('admin.custom-orders.index')
            ->with('success', 'Harga dasar berhasil diperbarui. Harga baru berlaku untuk permintaan berikutnya.');
    }

    public function deleteBasePrice(string $ukuran)
    {
        DB::transaction(function () use ($ukuran) {
            $harga = CustomCakeBasePrice::query()
                ->lockForUpdate()
                ->findOrFail($ukuran);
            $jumlahUkuran = CustomCakeBasePrice::query()
                ->lockForUpdate()
                ->count();

            if ($jumlahUkuran <= 1) {
                throw ValidationException::withMessages([
                    'ukuran_cm' => 'Setidaknya satu ukuran kue harus tersedia.',
                ]);
            }

            $harga->delete();
        });

        return redirect()
            ->route('admin.custom-orders.index')
            ->with('success', "Ukuran {$ukuran} cm dihapus. Permintaan yang sudah masuk tetap memakai harga sebelumnya.");
    }

    public function adminShow($id)
    {
        $permintaan = CustomCakeOrder::with('user')->findOrFail($id);

        return view('custom_cake_admin_show', compact('permintaan'));
    }

    public function quote(Request $request, $id)
    {
        $request->merge([
            'biaya_dekorasi' => NominalRupiah::normalisasi(
                $request->input('biaya_dekorasi')
            ),
        ]);

        $validated = $request->validate([
            'aksi' => ['required', Rule::in(['tawarkan', 'tolak'])],
            'biaya_dekorasi' => [
                Rule::requiredIf($request->input('aksi') === 'tawarkan'),
                'nullable',
                'integer',
                'min:0',
                'max:999999999',
            ],
            'catatan_admin' => [
                Rule::requiredIf($request->input('aksi') === 'tolak'),
                'nullable',
                'string',
                'max:1000',
            ],
        ], [
            'biaya_dekorasi.required' => 'Biaya dekorasi wajib diisi sebelum penawaran dikirim.',
            'catatan_admin.required' => 'Alasan penolakan wajib disampaikan kepada pelanggan.',
        ]);

        $permintaan = DB::transaction(function () use ($id, $validated) {
            $permintaan = CustomCakeOrder::where('id_custom_order', $id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                $permintaan->status === CustomCakeOrder::STATUS_MENUNGGU_KONFIRMASI,
                403,
                'Permintaan ini sudah diproses.'
            );

            if ($validated['aksi'] === 'tolak') {
                $permintaan->update([
                    'status' => CustomCakeOrder::STATUS_DITOLAK,
                    'catatan_admin' => $validated['catatan_admin'],
                    'biaya_dekorasi' => null,
                    'total_harga' => null,
                ]);
            } else {
                $biayaDekorasi = (int) $validated['biaya_dekorasi'];

                $permintaan->update([
                    'status' => CustomCakeOrder::STATUS_MENUNGGU_PERSETUJUAN,
                    'biaya_dekorasi' => $biayaDekorasi,
                    'total_harga' => (int) $permintaan->harga_dasar + $biayaDekorasi,
                    'catatan_admin' => $validated['catatan_admin'] ?? null,
                ]);
            }

            return $permintaan;
        });

        return redirect()
            ->route('admin.custom-orders.show', $permintaan->id_custom_order)
            ->with('success', $validated['aksi'] === 'tolak'
                ? 'Permintaan ditolak dan alasan telah disampaikan.'
                : 'Penawaran harga berhasil dikirim kepada pelanggan.');
    }
}
