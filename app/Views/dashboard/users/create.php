<!-- Create User Subview -->
<div class="container mt-5">

    <h2 class="mb-4">Create User</h2> <!--to change-->

    <form id="create-user-form" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" id="email">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>
        <div class="mb-3">
            <label for="repeated-password" class="form-label">Repeat Password</label>
            <input type="password" class="form-control" name="repeated-password" id="repeated-password">
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select class="form-select" name="role" id="role">
                <?php foreach ($data['roles'] as $role): ?>
                    <option value="<?= $role ?>"> <?= $this->e(ucfirst($role)) ?> </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Add</button>
        <a href="<?= url('/dashboard/users') ?>" class="btn btn-secondary ms-2">Cancel</a>
    </form>

    <div id="create-user-feedback" class="mt-3 text-danger"></div>
    
</div>

