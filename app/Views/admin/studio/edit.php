<div class="container mt-4">
    <a href="<?= base_url('admin/studios') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Studio</a>
    <h2 class="mb-4">Edit Studio: <?= esc($studio['nama_studio']) ?></h2>

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
        <form action="<?= base_url('admin/studios/update/' . esc($studio['id'])) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT"> <!-- Method spoofing untuk PUT -->
            <div class="mb-3">
                <label for="nama_studio" class="form-label">Nama Studio:</label>
                <input type="text" id="nama_studio" name="nama_studio" class="form-control" value="<?= old('nama_studio', $studio['nama_studio']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control"><?= old('deskripsi', $studio['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat:</label>
                <input type="text" id="alamat" name="alamat" class="form-control" value="<?= old('alamat', $studio['alamat']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="harga_per_jam" class="form-label">Harga per Jam:</label>
                <input type="number" id="harga_per_jam" name="harga_per_jam" class="form-control" value="<?= old('harga_per_jam', $studio['harga_per_jam']) ?>" step="any" required min="0">
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto Studio (biarkan kosong jika tidak ingin mengubah):</label>
                <?php if ($studio['foto']): ?>
                    <div class="mb-2">
                        <img src="<?= base_url('uploads/studio_images/' . esc($studio['foto'])) ?>" alt="Current Foto" class="img-thumbnail" style="max-width: 150px;">
                        <small class="text-muted d-block mt-1">Current: <?= esc($studio['foto']) ?></small>
                    </div>
                <?php endif; ?>
                <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="status_aktif" class="form-label">Status Aktif:</label>
                <select id="status_aktif" name="status_aktif" class="form-select" required>
                    <option value="aktif" <?= old('status_aktif', $studio['status_aktif']) == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= old('status_aktif', $studio['status_aktif']) == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Update Studio</button>
            </div>
        </form>
    </div>
</div>