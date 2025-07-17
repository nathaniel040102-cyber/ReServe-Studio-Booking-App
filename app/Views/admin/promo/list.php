<div class="container mt-4">
    <a href="<?= base_url('admin') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Dashboard Admin</a>
    <h2 class="mb-4">Manajemen Promo dan Diskon</h2>
    <p class="mb-4">
        <a href="<?= base_url('admin/promos/new') ?>" class="btn btn-success">+ Tambah Promo Baru</a>
    </p>

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

    <?php if (empty($promos)): ?>
        <div class="alert alert-info" role="alert">
            Belum ada promo.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kode Promo</th>
                        <th>Tipe Diskon</th>
                        <th>Nilai Diskon</th>
                        <th>Aktif Dari</th>
                        <th>Aktif Hingga</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($promos as $promo): ?>
                        <tr>
                            <td><?= esc($promo['kode_promo']) ?></td>
                            <td><?= esc(ucfirst($promo['tipe_diskon'])) ?></td>
                            <td>
                                <?php if ($promo['tipe_diskon'] == 'persen'): ?>
                                    <?= esc($promo['nilai_diskon']) ?>%
                                <?php else: ?>
                                    Rp<?= number_format(esc($promo['nilai_diskon']), 0, ',', '.') ?>
                                <?php endif; ?>
                            </td>
                            <td><?= esc(date('Y-m-d H:i', strtotime($promo['tanggal_mulai']))) ?></td>
                            <td><?= esc(date('Y-m-d H:i', strtotime($promo['tanggal_berakhir']))) ?></td>
                            <td><span class="badge bg-<?= $promo['status_aktif'] == 'aktif' ? 'success' : 'danger' ?>"><?= esc(ucfirst($promo['status_aktif'])) ?></span></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/promos/edit/' . esc($promo['id'])) ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                <form action="<?= base_url('admin/promos/delete/' . esc($promo['id'])) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo ini?');" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>