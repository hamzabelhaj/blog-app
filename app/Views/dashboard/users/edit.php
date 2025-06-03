<!-- Edit User Subview -->
<div class="container mt-5">

    <h2 class="mb-4">Edit User</h2>

    <form id="user-update-form" data-user-id="<?= $this->e($data['user']['id']) ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" value="<?= $this->e($data['user']['username']) ?? '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" name="email" id="email" value="<?= $this->e($data['user']['email']) ?? '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="role">
                <?php foreach ($data['roles'] as $role): ?>
                    <option value="<?= $role ?>" <?= ($data['user']['role'] ?? '') === $role ? 'selected' : '' ?>>
                        <?= $this->e(ucfirst($role))?> 
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" id="update-user-btn">Update User</button>
        <a href="<?= url('/dashboard/users') ?>" class="btn btn-secondary ms-2">Cancel</a>
        <div id="user-update-feedback" class="mt-3 text-danger"></div>
    </form>

    <div id="edit-user-feedback" class="mt-3 text-danger"></div>
    
</div>

