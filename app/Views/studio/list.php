<div class="container mt-4"> <!-- Pastikan ini di dalam main-content-wrapper dari header.php -->
    <h2 class="mb-4">Daftar Studio Musik ReServe</h2>

    <?php if (empty($studios)): ?>
        <div class="alert alert-info" role="alert">
            Belum ada studio yang tersedia saat ini.
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php foreach ($studios as $studio): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <?php if ($studio['foto']): ?>
                            <img src="<?= base_url('uploads/studio_images/' . esc($studio['foto'])) ?>" class="card-img-top studio-card-img" alt="<?= esc($studio['nama_studio']) ?>">
                        <?php else: ?>
                            <div class="d-flex justify-content-center align-items-center bg-light studio-card-img-placeholder">
                                <span class="text-muted">No Image</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($studio['nama_studio']) ?></h5>
                            <p class="card-text text-muted"><?= esc(word_limiter($studio['deskripsi'], 20)) ?></p>
                            <p class="card-text"><strong>Harga:</strong> Rp<?= number_format(esc($studio['harga_per_jam']), 0, ',', '.') ?> / jam</p>
                            <a href="<?= base_url('studios/' . esc($studio['id'])) ?>" class="btn btn-primary mt-2">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>