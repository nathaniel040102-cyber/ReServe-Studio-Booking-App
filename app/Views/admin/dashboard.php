<div class="container main-content-wrapper mt-4">
    <h2 class="mb-4">Dashboard Admin</h2>

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

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
        <!-- Kartu Total Studio -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Studio</h3>
                    <p class="card-text display-4 my-3"><?= esc($total_studios) ?></p>
                    <a href="<?= base_url('admin/studios') ?>" class="btn btn-outline-primary btn-sm">Lihat Semua Studio &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Kartu Total Pengguna -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Pengguna</h3>
                    <p class="card-text display-4 my-3"><?= esc($total_users) ?></p>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary btn-sm">Lihat Semua Pengguna &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Kartu Total Promo -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Promo</h3>
                    <p class="card-text display-4 my-3"><?= esc($total_promos) ?></p>
                    <a href="<?= base_url('admin/promos') ?>" class="btn btn-outline-primary btn-sm">Lihat Semua Promo &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Kartu Total Pemesanan -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Pemesanan</h3>
                    <p class="card-text display-4 my-3"><?= esc($total_bookings ?? 'N/A') ?></p>
                    <a href="<?= base_url('admin/bookings') ?>" class="btn btn-outline-primary btn-sm mb-2">Lihat Semua Pemesanan &rarr;</a>
                    <a href="<?= base_url('admin/bookings/validate') ?>" class="btn btn-outline-info btn-sm">Validasi QR Code &rarr;</a>
                </div>
            </div>
        </div>

        <!-- Kartu Total Ulasan -->
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title">Total Ulasan</h3>
                    <p class="card-text display-4 my-3"><?= esc($total_reviews ?? 'N/A') ?></p>
                    <a href="<?= base_url('admin/reviews') ?>" class="btn btn-outline-primary btn-sm">Lihat Semua Ulasan &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>