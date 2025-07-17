<div class="container mt-4">
    <a href="<?= base_url('admin') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Dashboard Admin</a>
    <h2 class="mb-4">Manajemen Pengguna</h2>
    <!-- Tombol Tambah Pengguna bisa ditambahkan di sini jika Anda ingin Admin bisa mendaftar pengguna baru -->
    <!-- <p class="mb-4"><a href="<?= base_url('admin/users/new') ?>" class="btn btn-success">+ Tambah Pengguna Baru</a></p> -->

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

    <?php if (empty($users)): ?>
        <div class="alert alert-info" role="alert">
            Belum ada pengguna.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= esc($user['nama']) ?></td>
                            <td><?= esc($user['email']) ?></td>
                            <td>
                                <span class="badge bg-<?= $user['role_id'] == 1 ? 'info' : 'secondary' ?>">
                                    <?= $user['role_id'] == 1 ? 'Admin' : 'Pengguna Biasa' ?>
                                </span>
                            </td>
                            <td><?= esc(date('Y-m-d H:i', strtotime($user['created_at']))) ?></td>
                            <td class="text-center">
                                <a href="<?= base_url('admin/users/edit/' . esc($user['id'])) ?>" class="btn btn-warning btn-sm me-1">Edit</a>
                                <form action="<?= base_url('admin/users/delete/' . esc($user['id'])) ?>" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" class="d-inline">
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