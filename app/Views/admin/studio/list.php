<div class="container mt-4">
    <a href="<?= base_url('admin') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Dashboard Admin</a>
    <h2 class="mb-4">Manajemen Studio</h2>
    <p class="mb-4">
        <a href="<?= base_url('admin/studios/new') ?>" class="btn btn-success">+ Tambah Studio Baru</a>
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

    <?php if (empty($studios)): ?>
        <div class="alert alert-info" role="alert">
            Belum ada studio.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama Studio</th>
                        <th>Harga/Jam</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($studios as $studio): ?>
                        <tr>
                            <td><?= esc($studio['nama_studio']) ?></td>
                            <td>Rp<?= number_format(esc($studio['harga_per_jam']), 0, ',', '.') ?></td>
                            <td><span class="badge bg-<?= $studio['status_aktif'] == 'aktif' ? 'success' : 'danger' ?>"><?= esc(ucfirst($studio['status_aktif'])) ?></span></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/studios/edit/' . esc($studio['id'])) ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                <form action="<?= base_url('admin/studios/delete/' . esc($studio['id'])) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus studio ini?');" class="d-inline">
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