<!-- Edit Current User's profile Subview -->
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">

            <h3 class="card-title mb-3">Edit Profile</h3>
            <form id="profile-update-form" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input name="username" id="username" type="text" class="form-control"
                        value="<?= $this->e($user['username']) ?>">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input name="email" id="email" type="email" class="form-control"
                        value="<?= $this->e($user['email']) ?>">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password <small class="text-muted">(leave empty to keep current password)</small></label>
                    <input name="password" id="password" type="password" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Save Changes</button>
                <a href="<?= url('/dashboard/profile') ?>" class="btn btn-secondary ms-2">Cancel</a>

                <div id="profile-update-feedback" class="mt-3 text-danger"></div>

            </form>
        </div>
    </div>
</div>

