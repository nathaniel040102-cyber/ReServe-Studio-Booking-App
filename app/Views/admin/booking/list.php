<div class="container mt-4">
    <a href="<?= base_url('admin') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Dashboard Admin</a>
    <h2 class="mb-4">Manajemen Pemesanan</h2>
    <p class="mb-4">
        <a href="<?= base_url('admin/bookings/validate') ?>" class="btn btn-info text-white">Validasi QR Code &rarr;</a>
    </p>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success" role="alert">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger" role="alert">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('info')): ?>
        <div class="alert alert-info" role="alert">
            <?= session()->getFlashdata('info') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info" role="alert">
            Belum ada pemesanan.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Booking</th>
                        <th>Pengguna</th>
                        <th>Studio</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Total Harga</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= esc($booking['id']) ?></td>
                            <td><?= esc($booking['user_nama']) ?></td>
                            <td><?= esc($booking['studio_nama']) ?></td>
                            <td><?= esc(date('Y-m-d', strtotime($booking['tanggal_booking']))) ?></td>
                            <td><?= esc(date('H:i', strtotime($booking['jam_mulai']))) ?> - <?= esc(date('H:i', strtotime($booking['jam_selesai']))) ?></td>
                            <td>Rp<?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></td>
                            <td>
                                <span class="fw-bold text-<?= $booking['status_pembayaran'] == 'pending' ? 'warning' : ($booking['status_pembayaran'] == 'lunas' ? 'success' : 'danger') ?>;">
                                    <?= esc(ucfirst($booking['status_pembayaran'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/bookings/view/' . esc($booking['id'])) ?>" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>