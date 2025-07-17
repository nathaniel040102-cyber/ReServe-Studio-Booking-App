<div class="container mt-4">
    <a href="<?= base_url('admin/promos') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Promo</a>
    <h2 class="mb-4">Edit Promo: <?= esc($promo['kode_promo']) ?></h2>

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
        <form action="<?= base_url('admin/promos/update/' . esc($promo['id'])) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT"> <!-- Method spoofing untuk PUT -->
            <div class="mb-3">
                <label for="kode_promo" class="form-label">Kode Promo:</label>
                <input type="text" id="kode_promo" name="kode_promo" class="form-control" value="<?= old('kode_promo', $promo['kode_promo']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="deskripsi" class="form-label">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" class="form-control"><?= old('deskripsi', $promo['deskripsi']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="tipe_diskon" class="form-label">Tipe Diskon:</label>
                <select id="tipe_diskon" name="tipe_diskon" class="form-select" required>
                    <option value="persen" <?= old('tipe_diskon', $promo['tipe_diskon']) == 'persen' ? 'selected' : '' ?>>Persentase (%)</option>
                    <option value="nominal" <?= old('tipe_diskon', $promo['tipe_diskon']) == 'nominal' ? 'selected' : '' ?>>Nominal (Rp)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="nilai_diskon" class="form-label">Nilai Diskon:</label>
                <input type="number" id="nilai_diskon" name="nilai_diskon" class="form-control" value="<?= old('nilai_diskon', $promo['nilai_diskon']) ?>" step="any" required min="0">
            </div>
            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai (YYYY-MM-DD HH:MM):</label>
                <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" class="form-control" value="<?= old('tanggal_mulai', date('Y-m-d\TH:i', strtotime($promo['tanggal_mulai']))) ?>" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_berakhir" class="form-label">Tanggal Berakhir (YYYY-MM-DD HH:MM):</label>
                <input type="datetime-local" id="tanggal_berakhir" name="tanggal_berakhir" class="form-control" value="<?= old('tanggal_berakhir', date('Y-m-d\TH:i', strtotime($promo['tanggal_berakhir']))) ?>" required>
            </div>
            <div class="mb-3">
                <label for="status_aktif" class="form-label">Status Aktif:</label>
                <select id="status_aktif" name="status_aktif" class="form-select" required>
                    <option value="aktif" <?= old('status_aktif', $promo['status_aktif']) == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="nonaktif" <?= old('status_aktif', $promo['status_aktif']) == 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Update Promo</button>
            </div>
        </form>
    </div>
</div>