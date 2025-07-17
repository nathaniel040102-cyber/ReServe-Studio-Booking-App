<div class="container main-content-wrapper mt-4">
    <h2 class="mb-4">Riwayat Pemesanan Saya</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info" role="alert">
            Anda belum memiliki riwayat pemesanan.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 g-4">
            <?php foreach ($bookings as $booking): ?>
                <div class="col">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Pemesanan #<?= esc($booking['id']) ?> - <?= esc($booking['studio_nama']) ?></h5>
                            <p class="card-text mb-1"><strong>Tanggal:</strong> <?= esc(date('Y-m-d', strtotime($booking['tanggal_booking']))) ?></p>
                            <p class="card-text mb-1"><strong>Waktu:</strong> <?= esc(date('H:i', strtotime($booking['jam_mulai']))) ?> - <?= esc(date('H:i', strtotime($booking['jam_selesai']))) ?></p>
                            <p class="card-text mb-1"><strong>Total Harga:</strong> Rp<?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></p>
                            <p class="card-text mb-1"><strong>Status:</strong>
                                <span class="fw-bold text-<?= $booking['status_pembayaran'] == 'pending' ? 'warning' : ($booking['status_pembayaran'] == 'lunas' ? 'success' : 'danger') ?>;">
                                    <?= esc(ucfirst($booking['status_pembayaran'])) ?>
                                </span>
                            </p>

                            <?php if ($booking['qr_code_path']): ?>
                                <div class="mt-3 text-center">
                                    <h6 class="card-subtitle mb-2 text-muted">QR Code Anda:</h6>
                                    <img src="<?= base_url('qrcodes/' . esc($booking['qr_code_path'])) ?>" alt="QR Code Booking" class="img-fluid border p-2 qr-code-img" style="max-width: 150px;">
                                </div>
                            <?php endif; ?>

                            <?php
                            // Variabel untuk kondisi
                            $currentDateTime = strtotime(date('Y-m-d H:i:s'));
                            $bookingEndDateTime = strtotime($booking['tanggal_booking'] . ' ' . $booking['jam_selesai']);
                            ?>

                            <?php if ($booking['status_pembayaran'] == 'lunas' && $currentDateTime > $bookingEndDateTime && !$booking['has_reviewed']): ?>
                                <p class="mt-3">
                                    <a href="<?= base_url('reviews/create/' . esc($booking['id'])) ?>" class="btn btn-primary btn-sm">Berikan Ulasan</a>
                                </p>
                            <?php elseif ($booking['has_reviewed']): ?>
                                <p class="mt-3 text-muted">Anda sudah mengulas pemesanan ini. Status ulasan: <?= esc(ucfirst($booking['review_status'] ?? 'Pending')) ?></p>
                            <?php elseif ($booking['status_pembayaran'] == 'pending'): ?>
                                <p class="mt-3 text-muted">Ulasan dapat diberikan setelah pemesanan lunas dan selesai.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>