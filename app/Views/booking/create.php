<div class="container main-content-wrapper mt-4">
    <a href="<?= base_url('studios/' . esc($studio['id'])) ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Detail Studio</a>

    <div class="card shadow-lg p-4">
        <h2 class="card-title mb-3">Pesan Studio: <?= esc($studio['nama_studio']) ?></h2>
        <p class="card-text">Harga: Rp<?= number_format(esc($studio['harga_per_jam']), 0, ',', '.') ?> / jam</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('booking/store') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="studio_id" value="<?= esc($studio['id']) ?>">

            <div class="mb-3">
                <label for="tanggal_booking" class="form-label">Tanggal Booking:</label>
                <input type="date" id="tanggal_booking" name="tanggal_booking" class="form-control" value="<?= old('tanggal_booking', date('Y-m-d')) ?>" required min="<?= date('Y-m-d') ?>">
            </div>

            <div class="mb-3">
                <label for="jam_mulai" class="form-label">Jam Mulai:</label>
                <input type="time" id="jam_mulai" name="jam_mulai" class="form-control" value="<?= old('jam_mulai', '10:00') ?>" required>
            </div>

            <div class="mb-3">
                <label for="durasi_jam" class="form-label">Durasi (jam):</label>
                <input type="number" id="durasi_jam" name="durasi_jam" class="form-control" value="<?= old('durasi_jam', 1) ?>" min="0.5" step="0.5" required>
            </div>

            <!-- Input Kode Promo -->
            <div class="mb-3">
                <label for="kode_promo" class="form-label">Kode Promo (Opsional):</label>
                <input type="text" id="kode_promo" name="kode_promo" class="form-control" value="<?= old('kode_promo') ?>">
                <div class="form-text">Masukkan kode promo jika ada.</div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Konfirmasi Pemesanan</button>
            </div>
        </form>
    </div>
</div>