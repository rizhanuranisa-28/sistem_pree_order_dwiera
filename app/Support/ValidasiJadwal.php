<?php

namespace App\Support;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ValidasiJadwal
{
    // ===== ATURAN JADWAL DWIERA =====
    const JAM_BUKA = '08:00';
    const JAM_TUTUP = '20:00';

    const MINIMAL_JAM_SEBELUM = 3;

    // Maksimal 2 pesanan dalam satu jam.
    const MAKS_PESANAN_PER_SLOT = 2;
    // =================================

    /**
     * Mengunci slot berdasarkan tanggal dan jam.
     *
     * Pesanan pada slot yang sama akan diproses bergantian
     * agar tidak terjadi kelebihan kapasitas.
     */
    public static function denganKunci(
        string $tanggal,
        string $jam,
        callable $aksi
    ) {
        $waktu = Carbon::parse($jam);

        $namaKunci = 'dwiera_slot_'
            . $tanggal
            . '_'
            . $waktu->format('H');

        $hasil = DB::selectOne(
            'SELECT GET_LOCK(?, 10) AS berhasil',
            [$namaKunci]
        );

        if (!$hasil || (int) $hasil->berhasil !== 1) {
            throw new \RuntimeException(
                'Slot sedang diproses pelanggan lain. Silakan coba kembali.'
            );
        }

        try {
            return $aksi();
        } finally {
            DB::selectOne(
                'SELECT RELEASE_LOCK(?) AS dilepas',
                [$namaKunci]
            );
        }
    }

    /**
     * Memeriksa apakah jadwal pengambilan tersedia.
     *
     * Mengembalikan pesan error jika jadwal tidak valid.
     * Mengembalikan null jika jadwal bisa digunakan.
     *
     * $kecualiIdJadwal digunakan ketika mengedit jadwal
     * pesanan sendiri agar tidak dihitung sebagai pesanan lain.
     */
    public static function cek(
        string $tanggal,
        string $jam,
        ?int $kecualiIdJadwal = null,
        ?string $nomorTelepon = null
    ): ?string {

        try {
            $waktu = Carbon::createFromFormat(
                'Y-m-d H:i',
                $tanggal . ' ' . $jam
            );
        } catch (\Throwable $e) {
            return 'Format tanggal atau jam pengambilan tidak valid.';
        }

        $sekarang = now();

        // Tidak boleh memilih jadwal yang sudah lewat.
        if ($waktu->lt($sekarang)) {
            return 'Jadwal pengambilan sudah lewat. Pilih tanggal dan jam yang akan datang.';
        }

        // Minimal pemesanan 3 jam sebelumnya.
        $palingCepat = $sekarang->copy()
            ->addHours(self::MINIMAL_JAM_SEBELUM);

        if ($waktu->lt($palingCepat)) {
            return 'Pengambilan minimal '
                . self::MINIMAL_JAM_SEBELUM
                . ' jam dari sekarang. Paling cepat: '
                . $palingCepat->format('d/m/Y H:i')
                . '.';
        }

        // Pemeriksaan jam operasional.
        $jamPilihan = $waktu->format('H:i');

        if (
            $jamPilihan < self::JAM_BUKA ||
            $jamPilihan > self::JAM_TUTUP
        ) {
            return 'Jam pengambilan harus antara '
                . self::JAM_BUKA
                . ' dan '
                . self::JAM_TUTUP
                . '.';
        }

        /*
         * Ambil pesanan pada tanggal dan jam yang sama.
         *
         * Pesanan online:
         * menggunakan users.id_users.
         *
         * Pesanan offline:
         * menggunakan customers.id_customer.
         *
         * Nomor telepon digunakan untuk mengenali customer
         * yang sama meskipun memesan melalui jalur berbeda.
         */
        $pesananLama = DB::table('jadwal_pengambilan')
            ->join(
                'pesanan',
                'jadwal_pengambilan.id_jadwal',
                '=',
                'pesanan.id_jadwal'
            )
            ->leftJoin(
                'users',
                'pesanan.id_users',
                '=',
                'users.id_users'
            )
            ->leftJoin(
                'customers',
                'pesanan.id_customer',
                '=',
                'customers.id_customer'
            )
            ->whereDate(
                'jadwal_pengambilan.tanggal_pengambilan',
                $tanggal
            )
            ->whereRaw(
                'HOUR(jadwal_pengambilan.jam_pengambilan) = ?',
                [$waktu->hour]
            )
            ->when(
                $kecualiIdJadwal,
                function ($query) use ($kecualiIdJadwal) {
                    $query->where(
                        'jadwal_pengambilan.id_jadwal',
                        '!=',
                        $kecualiIdJadwal
                    );
                }
            )
            ->select(
                'jadwal_pengambilan.id_jadwal',
                'pesanan.id_users',
                'pesanan.id_customer',
                DB::raw(
                    'COALESCE(users.nomor_telepon, customers.nomor_telepon) AS nomor_telepon'
                )
            )
            ->get();

        // Maksimal dua pesanan dalam satu slot jam.
        if ($pesananLama->count() >= self::MAKS_PESANAN_PER_SLOT) {
            return 'Jadwal pukul '
                . $waktu->format('H:00')
                . ' pada tanggal '
                . $waktu->format('d/m/Y')
                . ' sudah penuh. Silakan pilih jam lain.';
        }

        /*
         * Normalisasi nomor telepon:
         * 0812xxxx dan 62812xxxx dianggap sama.
         */
        $normalisasiNomor = function ($nomor) {
            if (!$nomor) {
                return null;
            }

            $nomor = preg_replace('/[^0-9]/', '', $nomor);

            if (str_starts_with($nomor, '62')) {
                $nomor = '0' . substr($nomor, 2);
            }

            return $nomor;
        };

        $nomorBaru = $normalisasiNomor($nomorTelepon);

        if ($nomorBaru) {
            foreach ($pesananLama as $pesanan) {
                $nomorLama = $normalisasiNomor(
                    $pesanan->nomor_telepon
                );

                if ($nomorLama && $nomorBaru === $nomorLama) {
                    return 'Kamu sudah memiliki pesanan pada jam '
                        . $waktu->format('H:00')
                        . ' tanggal '
                        . $waktu->format('d/m/Y')
                        . '. Silakan pilih jam lain.';
                }
            }
        }

        return null;
    }
}