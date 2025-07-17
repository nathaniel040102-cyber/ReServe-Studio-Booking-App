<div class="container main-content-wrapper mt-4 text-center">
    <a href="<?= base_url('my-bookings') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Riwayat Pemesanan</a>

    <div class="card shadow-lg p-4">
        <h2 class="card-title mb-4">Pemesanan Berhasil!</h2>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('promo_applied')): ?>
            <div class="alert alert-info">
                <?= session()->getFlashdata('promo_applied') ?>
            </div>
        <?php endif; ?>


        <div class="card-body text-start">
            <h3 class="card-subtitle mb-3">Detail Pemesanan Anda:</h3>
            <p class="card-text"><strong>Studio:</strong> <?= esc($studio['nama_studio']) ?></p>
            <p class="card-text"><strong>Tanggal:</strong> <?= esc($booking['tanggal_booking']) ?></p>
            <p class="card-text"><strong>Waktu:</strong> <?= esc(date('H:i', strtotime($booking['jam_mulai']))) ?> - <?= esc(date('H:i', strtotime($booking['jam_selesai']))) ?> (<?= esc($booking['durasi_jam']) ?> jam)</p>
            <p class="card-text"><strong>Total Harga:</strong> Rp<?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></p>

            <?php if (!empty($booking['promo_id']) && isset($promo) && !empty($promo)): ?>
                <p class="card-text"><strong>Promo Digunakan:</strong> <?= esc($promo['kode_promo']) ?> (Diskon:
                    <?php if ($promo['tipe_diskon'] == 'persen'): ?>
                        <?= esc($promo['nilai_diskon']) ?>%
                    <?php else: ?>
                        Rp<?= number_format(esc($promo['nilai_diskon']), 0, ',', '.') ?>
                    <?php endif; ?>
                )</p>
            <?php endif; ?>

            <p class="card-text"><strong>Status Pembayaran:</strong>
                <span class="fw-bold text-<?= $booking['status_pembayaran'] == 'pending' ? 'warning' : ($booking['status_pembayaran'] == 'lunas' ? 'success' : 'danger') ?>;">
                    <?= esc(ucfirst($booking['status_pembayaran'])) ?>
                </span>
            </p>

            <?php if ($booking['qr_code_path']): ?>
                <h4 class="mt-4">Silakan tunjukkan QR Code ini saat check-in:</h4>
                <img src="<?= base_url('qrcodes/' . esc($booking['qr_code_path'])) ?>" alt="QR Code Booking" class="img-fluid border p-2 qr-code-img">
                <p class="text-muted mt-2 small">(Gambar QR Code ini disimpan di server lokal Anda)</p>
            <?php endif; ?>

            <div class="mt-4">
                <a href="<?= base_url('studios') ?>" class="btn btn-primary me-2">Kembali ke Daftar Studio</a>
                <a href="<?= base_url('my-bookings') ?>" class="btn btn-info text-white">Lihat Riwayat Pemesanan</a>
            </div>
        </div>
    </div>
</div>