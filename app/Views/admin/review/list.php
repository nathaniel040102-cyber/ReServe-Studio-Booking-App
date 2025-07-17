<div class="container mt-4">
    <a href="<?= base_url('admin') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Dashboard Admin</a>
    <h2 class="mb-4">Manajemen Ulasan Pengguna</h2>

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
    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (empty($reviews)): ?>
        <div class="alert alert-info" role="alert">
            Belum ada ulasan yang masuk.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID Ulasan</th>
                        <th>Pengguna</th>
                        <th>Studio</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td><?= esc($review['id']) ?></td>
                            <td><?= esc($review['user_nama']) ?></td>
                            <td><?= esc($review['studio_nama']) ?></td>
                            <td><?= esc($review['rating']) ?> Bintang</td>
                            <td><?= esc(word_limiter($review['ulasan'], 10)) ?></td>
                            <td>
                                <span class="fw-bold text-<?= $review['status_moderasi'] == 'pending' ? 'warning' : ($review['status_moderasi'] == 'disetujui' ? 'success' : 'danger') ?>;">
                                    <?= esc(ucfirst($review['status_moderasi'])) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="<?= base_url('admin/reviews/moderate/' . esc($review['id'])) ?>" method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="PUT"> <!-- Method spoofing untuk PUT -->
                                    <select name="status_moderasi" onchange="this.form.submit()" class="form-select form-select-sm d-inline-block w-auto">
                                        <option value="pending" <?= $review['status_moderasi'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="disetujui" <?= $review['status_moderasi'] == 'disetujui' ? 'selected' : '' ?>>Setujui</option>
                                        <option value="ditolak" <?= $review['status_moderasi'] == 'ditolak' ? 'selected' : '' ?>>Tolak</option>
                                    </select>
                                </form>
                                <!-- Anda bisa tambahkan tombol hapus jika diperlukan -->
                                <!--
                                <form action="<?= base_url('admin/reviews/delete/' . esc($review['id'])) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ulasan ini?');" class="d-inline ms-1">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                                -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>