<div class="container mt-4">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary mb-4">&larr; Kembali ke Daftar Pengguna</a>
    <h2 class="mb-4">Edit Pengguna: <?= esc($user['nama']) ?></h2>

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
        <form action="<?= base_url('admin/users/update/' . esc($user['id'])) ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="PUT"> <!-- Method spoofing untuk PUT -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap:</label>
                <input type="text" id="nama" name="nama" class="form-control" value="<?= old('nama', $user['nama']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= old('email', $user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password Baru (biarkan kosong jika tidak ingin mengubah):</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password Baru:</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="role_id" class="form-label">Role Pengguna:</label>
                <select id="role_id" name="role_id" class="form-select" required>
                    <option value="1" <?= old('role_id', $user['role_id']) == 1 ? 'selected' : '' ?>>Admin</option>
                    <option value="2" <?= old('role_id', $user['role_id']) == 2 ? 'selected' : '' ?>>Pengguna Biasa</option>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Update Pengguna</button>
            </div>
        </form>
    </div>
</div>