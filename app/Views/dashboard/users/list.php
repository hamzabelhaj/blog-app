<!--  List Users Subview -->
<h2> All Users</h2>

<a href="<?= url('dashboard/users/create') ?>" class="btn btn-sm btn-outline-success create-btn">Create new user</a>

<!-- Pagination  -->
<nav>
    <ul class="pagination justify-content-center user-pagination">
        <li class="page-item <?= ($data['currentPage'] === 1) ? 'disabled' : '' ?>"> <a class="page-link" data-page="<?= $data['currentPage'] - 1 ?>">Previous</a> </li>
        <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
            <li class="page-item <?= ($i === $data['currentPage']) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"> <?= $i ?> </a>
            </li>
        <?php endfor; ?>
        <li class="page-item  <?= ($data['currentPage'] >= $data['totalPages']) ? 'disabled' : '' ?>" ><a class="page-link" data-page="<?= $data['currentPage'] + 1 ?>" href="#">Next</a> </li>
    </ul>
</nav>

<!-- Users table -->
<table class="table table-striped table-hover table-bordered align-middle ">
    <thead class="table">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th style="width: 200px;">Actions</th>
        </tr>
    </thead>
    <tbody class="users-container">
        <?php foreach ($data['users'] as $user): ?>
            <tr class="user-row" data-user-id="<?= $this->e($user['id']) ?>">
                <td class="col-4"><?= $this->e($user['username']) ?></td>
                <td class="col-4"><?= $this->e($user['email']) ?></td>
                <td>
                    <span class="badge bg-<?=
                                            $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'editor' ? 'warning' : ($user['role'] === 'author' ? 'info' : 'secondary')) ?>">
                        <?= $this->e(ucfirst($user['role'])) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= url('dashboard/users/' . $user['id']) . '/edit' ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-user-btn" data-user-id="<?= $this->e($user['id']) ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<style>
    .create-btn {
        margin: 10px 0;
    }
</style>
