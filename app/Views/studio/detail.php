<div class="container main-content-wrapper mt-4">
    <a href="<?= base_url('studios') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Studio</a>

    <?php if (! empty($studio)): ?>
        <div class="card shadow-lg mb-4">
            <div class="card-body">
                <h2 class="card-title mb-4">Detail Studio: <?= esc($studio['nama_studio']) ?></h2>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <?php if ($studio['foto']): ?>
                            <img src="<?= base_url('uploads/studio_images/' . esc($studio['foto'])) ?>" class="img-fluid rounded studio-detail-img" alt="<?= esc($studio['nama_studio']) ?>">
                        <?php else: ?>
                            <div class="d-flex justify-content-center align-items-center bg-light rounded studio-card-img-placeholder" style="height: 300px;">
                                <span class="text-muted">No Image Available</span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">Deskripsi:</h5>
                        <p class="card-text"><?= nl2br(esc($studio['deskripsi'])) ?></p>
                        <p class="card-text"><strong>Alamat:</strong> <?= esc($studio['alamat']) ?></p>
                        <p class="card-text"><strong>Harga:</strong> Rp<?= number_format(esc($studio['harga_per_jam']), 0, ',', '.') ?> / jam</p>
                        <p class="mt-4">
                            <?php if (session()->get('isLoggedIn')): ?>
                                <a href="<?= base_url('booking/create/' . esc($studio['id'])) ?>" class="btn btn-success btn-lg">Booking Sekarang</a>
                            <?php else: ?>
                                <p class="text-muted">Silakan <a href="<?= base_url('login') ?>">login</a> untuk melakukan pemesanan.</p>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bagian Ulasan Pengguna -->
        <div class="card shadow-lg mt-5 p-4">
            <h3 class="card-title mb-4">Ulasan Pengguna (<?= count($reviews) ?>)</h3>
            <?php if (empty($reviews)): ?>
                <div class="alert alert-info" role="alert">
                    Belum ada ulasan untuk studio ini.
                </div>
            <?php else: ?>
                <?php foreach ($reviews as $review): ?>
                    <div class="mb-3 pb-3 border-bottom">
                        <p class="fw-bold mb-1"><?= esc($review['user_nama']) ?> - Rating: <?= esc($review['rating']) ?> Bintang</p>
                        <p class="fst-italic text-muted mb-1">"<?= esc($review['ulasan']) ?>"</p>
                        <small class="text-muted">Pada: <?= esc(date('Y-m-d H:i', strtotime($review['created_at']))) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning" role="alert">
            Studio tidak ditemukan.
        </div>
    <?php endif; ?>
</div>