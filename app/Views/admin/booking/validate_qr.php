<div class="container mt-4">
    <a href="<?= base_url('admin/bookings') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Pemesanan</a>
    <h2 class="mb-4">Validasi QR Code Booking</h2>
    <p class="text-muted">Silakan masukkan konten QR Code (misalnya ID booking atau string JSON dari QR).</p>

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

    <div class="card shadow-lg p-4">
        <form action="<?= base_url('admin/bookings/validate') ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="qr_content" class="form-label">Konten QR Code:</label>
                <input type="text" id="qr_content" name="qr_content" class="form-control" required>
                <div class="form-text">Contoh: {"booking_id":1,"user_id":1,...} atau hanya 1 (jika QR berisi ID)</div>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Validasi QR Code</button>
            </div>
        </form>
    </div>
</div>