<div
    id="pesanValidasiJadwal"
    role="alert"
    aria-live="polite"
    hidden
    style="margin-top: 6px; color: #b42318; font-size: 12px;"
></div>

<script>
    (function () {
        const tanggal = document.getElementById('tanggal_pengambilan');
        const jam = document.getElementById('jam_pengambilan');
        const pesan = document.getElementById('pesanValidasiJadwal');

        if (!tanggal || !jam || !pesan) {
            return;
        }

        const batasMinimal = @json($batasMinimalJadwal->format('Y-m-d H:i'));
        const teksPeringatan = @json($pesanJadwalMinimum);
        const jamBuka = @json(\App\Support\ValidasiJadwal::JAM_BUKA);
        const jamTutup = @json(\App\Support\ValidasiJadwal::JAM_TUTUP);

        function perbaruiValidasi() {
            const nilaiTanggal = tanggal.value;
            const nilaiJam = jam.value;

            jam.min = nilaiTanggal === batasMinimal.slice(0, 10)
                ? batasMinimal.slice(11)
                : jamBuka;
            jam.max = jamTutup;

            const terlaluCepat = nilaiTanggal &&
                nilaiJam &&
                (nilaiTanggal + ' ' + nilaiJam < batasMinimal);

            jam.setCustomValidity(terlaluCepat ? teksPeringatan : '');
            pesan.textContent = terlaluCepat ? teksPeringatan : '';
            pesan.hidden = !terlaluCepat;
        }

        tanggal.addEventListener('change', perbaruiValidasi);
        jam.addEventListener('input', perbaruiValidasi);
        jam.addEventListener('change', perbaruiValidasi);

        perbaruiValidasi();
    })();
</script>
