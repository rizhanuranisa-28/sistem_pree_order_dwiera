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

    public static function batasMinimal(?Carbon $sekarang = null): Carbon
    {
        $zonaWaktu = config('app.timezone', 'Asia/Jakarta');
        $batas = ($sekarang ?? Carbon::now($zonaWaktu))
            ->copy()
            ->addHours(self::MINIMAL_JAM_SEBELUM);

        if ($batas->format('s.u') !== '00.000000') {
            $batas->startOfMinute()->addMinute();
        }

        return $batas;
    }

    public static function waktuPalingCepat(?Carbon $sekarang = null): Carbon
    {
        $waktuPalingCepat = self::batasMinimal($sekarang);
        $jam = $waktuPalingCepat->format('H:i');

        if ($jam < self::JAM_BUKA) {
            return $waktuPalingCepat->setTime(8, 0);
        }

        if ($jam > self::JAM_TUTUP) {
            return $waktuPalingCepat
                ->addDay()
                ->setTime(8, 0);
        }

        return $waktuPalingCepat;
    }

    public static function pesanJadwalMinimum(?Carbon $sekarang = null): string
    {
        $sekarang = ($sekarang ?? Carbon::now(config('app.timezone', 'Asia/Jakarta')))
            ->copy();
        $waktuPalingCepat = self::waktuPalingCepat($sekarang);
        $pukul = $waktuPalingCepat->format('H.i');
        $bedaTanggal = $waktuPalingCepat->toDateString() !==
            $sekarang->toDateString();
        $tanggal = $bedaTanggal
            ? ' pada tanggal ' . $waktuPalingCepat->format('d/m/Y')
            : '';
        $instruksi = $bedaTanggal
            ? 'Silakan pilih tanggal tersebut pukul ' . $pukul . ' atau setelahnya.'
            : 'Silakan pilih pukul ' . $pukul . ' atau setelahnya.';

        return 'Maaf, pemesanan harus dilakukan minimal '
            . self::MINIMAL_JAM_SEBELUM
            . ' jam sebelum waktu pengambilan. Jadwal pengambilan paling awal yang tersedia adalah pukul '
            . $pukul
            . $tanggal
            . '. '
            . $instruksi;
    }

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
        if (DB::connection()->getDriverName() === 'sqlite') {
            return $aksi();
        }

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
                $tanggal . ' ' . $jam,
                config('app.timezone', 'Asia/Jakarta')
            );
        } catch (\Throwable $e) {
            return 'Format tanggal atau jam pengambilan tidak valid.';
        }

        $sekarang = Carbon::now(config('app.timezone', 'Asia/Jakarta'));

        // Tidak boleh memilih jadwal yang sudah lewat.
        if ($waktu->lt($sekarang)) {
            return 'Jadwal pengambilan sudah lewat. Pilih tanggal dan jam yang akan datang.';
        }

        // Minimal pemesanan 3 jam sebelumnya.
        $palingCepat = self::batasMinimal($sekarang);

        if ($waktu->lt($palingCepat)) {
            return self::pesanJadwalMinimum($sekarang);
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
            return 'Pesanan Penuh. Jadwal ini sudah mencapai batas maksimal. '
                . 'Silakan pilih jam pengambilan minimal 3 jam setelah pemesanan dilakukan.';
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