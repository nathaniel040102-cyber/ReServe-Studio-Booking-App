<div class="container mt-4">
    <a href="<?= base_url('admin/studios') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Studio</a>
    <h2 class="mb-4">Tambah Studio Baru</h2>

    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg p-4">
        <form action="<?= base_url('admin/studios/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="nama_studio" class="form-label">Nama Studio:</label>
                <input type="text" id="nama_studio" name="nama_studio" class="form-control" value="<?= old('nama_studio') ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control"><?= old('deskripsi') ?></textarea>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat:</label>
                <input type="text" id="alamat" name="alamat" class="form-control" value="<?= old('alamat') ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga_per_jam" class="form-label">Harga per Jam:</label>
                <input type="number" id="harga_per_jam" name="harga_per_jam" class="form-control" value="<?= old('harga_per_jam') ?>" step="any" required min="0">
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Studio:</label>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/*" required>
            </div>
            <div class="mb-3">
                <label for="status_aktif" class="form-label">Status Aktif:</label>
                <select id="status_aktif" name="status_aktif" class="form-select" required>
                    <option value="aktif" <?= old('status_aktif') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= old('status_aktif') == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Simpan Studio</button>
            </div>
        </form>
    </div>
</div>