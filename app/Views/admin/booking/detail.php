<div class="container mt-4">
    <a href="<?= base_url('admin/bookings') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Pemesanan</a>
    <h2 class="mb-4">Detail Pemesanan #<?= esc($booking['id']) ?></h2>

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
    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg p-4 mb-4">
        <h3 class="card-subtitle mb-3">Informasi Pemesanan:</h3>
        <p class="card-text"><strong>ID Booking:</strong> <?= esc($booking['id']) ?></p>
        <p class="card-text"><strong>Pengguna:</strong> <?= esc($user['nama'] ?? 'Tidak Ditemukan') ?> (<?= esc($user['email'] ?? 'N/A') ?>)</p>
        <p class="card-text"><strong>Studio:</strong> <?= esc($studio['nama_studio'] ?? 'Tidak Ditemukan') ?></p>
        <p class="card-text"><strong>Tanggal Booking:</strong> <?= esc(date('Y-m-d', strtotime($booking['tanggal_booking']))) ?></p>
        <p class="card-text"><strong>Waktu:</strong> <?= esc(date('H:i', strtotime($booking['jam_mulai']))) ?> - <?= esc(date('H:i', strtotime($booking['jam_selesai']))) ?></p>
        <p class="card-text"><strong>Durasi:</strong> <?= esc($booking['durasi_jam']) ?> jam</p>
        <p class="card-text"><strong>Total Harga:</strong> Rp<?= number_format(esc($booking['total_harga']), 0, ',', '.') ?></p>
        <?php if ($promo): ?>
            <p class="card-text"><strong>Promo Digunakan:</strong> <?= esc($promo['kode_promo']) ?> (Diskon: <?= $promo['tipe_diskon'] == 'persen' ? esc($promo['nilai_diskon']) . '%' : 'Rp' . number_format(esc($promo['nilai_diskon']), 0, ',', '.') ?>)</p>
        <?php endif; ?>
        <p class="card-text"><strong>Status Pembayaran:</strong>
            <span class="fw-bold text-<?= $booking['status_pembayaran'] == 'pending' ? 'warning' : ($booking['status_pembayaran'] == 'lunas' ? 'success' : 'danger') ?>;">
                <?= esc(ucfirst($booking['status_pembayaran'])) ?>
            </span>
        </p>
        <p class="card-text"><strong>Dibuat Pada:</strong> <?= esc(date('Y-m-d H:i', strtotime($booking['created_at']))) ?></p>
        <p class="card-text"><strong>Terakhir Diperbarui:</strong> <?= esc(date('Y-m-d H:i', strtotime($booking['updated_at']))) ?></p>

        <?php if ($booking['qr_code_path']): ?>
            <h4 class="mt-4">QR Code Booking:</h4>
            <img src="<?= base_url('qrcodes/' . esc($booking['qr_code_path'])) ?>" alt="QR Code Booking" class="img-fluid border p-2" style="max-width: 250px;">
            <p class="text-muted mt-2 small">(Gambar QR Code ini disimpan di server lokal Anda)</p>
        <?php endif; ?>
    </div>

    <div class="card shadow-lg p-4">
        <h3 class="card-subtitle mb-3">Perbarui Status Pembayaran:</h3>
        <form action="<?= base_url('admin/bookings/update-status/' . esc($booking['id'])) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT"> <!-- Method spoofing untuk PUT -->
            <div class="mb-3">
                <label for="status_pembayaran" class="form-label">Status Baru:</label>
                <select id="status_pembayaran" name="status_pembayaran" class="form-select" required>
                    <option value="pending" <?= old('status_pembayaran', $booking['status_pembayaran']) == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="lunas" <?= old('status_pembayaran', $booking['status_pembayaran']) == 'lunas' ? 'selected' : '' ?>>Lunas</option>
                    <option value="batal" <?= old('status_pembayaran', $booking['status_pembayaran']) == 'batal' ? 'selected' : '' ?>>Batal</option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
</div>