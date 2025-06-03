<!-- Show Current User's profile Subview -->
<div class="card shadow-sm">
    <div class="card-body">

        <h3 class="card-title mb-3">My Profile</h3>
        <p><strong>Username:</strong> <?= $this->e($user['username']) ?></p>
        <p><strong>Email:</strong> <?= $this->e($user['email']) ?></p>
        <p><strong>Role:</strong> <?= $this->e(ucfirst($user['role'])) ?></p>
        <div id="delete-profile-feedback" class="mt-3 text-danger"></div>
        <div>
            <a href="<?= url('dashboard/profile/edit') ?>" class="btn btn-primary mt-3">Edit Profile</a> <!--ajax-->
            <?php if($user['role'] !== 'admin'): ?>
            <a href="#" class="btn btn-primary btn-danger mt-3" id="delete-profile">Delete Profile</a> <!--ajax-->
            <?php endif; ?>
        </div>

    </div>
</div>

