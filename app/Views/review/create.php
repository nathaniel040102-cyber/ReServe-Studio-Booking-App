<div class="container main-content-wrapper mt-4">
    <a href="<?= base_url('my-bookings') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Riwayat Pemesanan</a>

    <div class="card shadow-lg p-4">
        <h2 class="card-title mb-3">Berikan Ulasan untuk Studio: <?= esc($studio['nama_studio']) ?></h2>
        <p class="card-text">Pemesanan Anda: <strong>#<?= esc($booking['id']) ?></strong> pada tanggal <strong><?= esc(date('Y-m-d', strtotime($booking['tanggal_booking']))) ?></strong>, jam <strong><?= esc(date('H:i', strtotime($booking['jam_mulai']))) ?> - <?= esc(date('H:i', strtotime($booking['jam_selesai']))) ?></strong>.</p>

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

        <form action="<?= base_url('reviews/store') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="booking_id" value="<?= esc($booking['id']) ?>">

            <div class="mb-3">
                <label for="rating" class="form-label">Rating (1-5 Bintang):</label>
                <select id="rating" name="rating" class="form-select" required>
                    <option value="">Pilih Rating</option>
                    <option value="5" <?= old('rating') == 5 ? 'selected' : '' ?>>5 Bintang - Sangat Bagus</option>
                    <option value="4" <?= old('rating') == 4 ? 'selected' : '' ?>>4 Bintang - Bagus</option>
                    <option value="3" <?= old('rating') == 3 ? 'selected' : '' ?>>3 Bintang - Cukup</option>
                    <option value="2" <?= old('rating') == 2 ? 'selected' : '' ?>>2 Bintang - Buruk</option>
                    <option value="1" <?= old('rating') == 1 ? 'selected' : '' ?>>1 Bintang - Sangat Buruk</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="ulasan" class="form-label">Ulasan Anda:</label>
                <textarea id="ulasan" name="ulasan" rows="5" class="form-control"><?= old('ulasan') ?></textarea>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Kirim Ulasan</button>
            </div>
        </form>
    </div>
</div>