<style>
    .qris-payment {
        max-width: 390px;
        margin: 18px auto;
        padding: 18px;
        border: 1px solid #ead8de;
        border-radius: 12px;
        background: white;
        text-align: center;
        color: #555;
        line-height: 1.6;
    }

    .qris-payment__image {
        display: block;
        width: 100%;
        max-width: 320px;
        height: auto;
        margin: 0 auto 12px;
    }

    .qris-payment p {
        margin: 8px 0 0;
        font-size: 13px;
    }
</style>

<div class="qris-payment" role="group" aria-label="Kode QRIS Dwiera Cake">
    <img
        class="qris-payment__image"
        src="{{ asset('images/qris_dwiera.png') }}"
        alt="Barcode QRIS Dwiera Cake"
    >
    <p>Scan QRIS ini menggunakan aplikasi bank atau dompet digital.</p>
    <p>Pastikan nama merchant dan nominal benar, lalu unggah bukti pembayaran untuk diverifikasi admin.</p>
</div>
