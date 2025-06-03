<!-- List Posts Subview  -->
<h2 class="table-title"><?= $data['isEditor'] ? 'All' : 'My' ?> Posts</h2>
<a href="<?= url('dashboard/posts/create') ?>" class="btn btn-sm btn-outline-success create-btn">Create new post</a>

<!-- Pagination  -->
<nav>
    <ul class="pagination justify-content-center dashboard-pagination">
        <!-- Previous -->
        <?php if ($data['currentPage'] <= 1): ?>
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="#" data-page="<?= $data['currentPage'] - 1 ?>">Previous</a>
            </li>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for ($i = 1; $i <= $data['totalPages']; $i++): ?>
            <li class="page-item <?= ($i === $data['currentPage']) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($data['currentPage'] >= $data['totalPages']): ?>
            <li class="page-item disabled"><span class="page-link">Next</span></li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="#" data-page="<?= $data['currentPage'] + 1 ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<!-- Posts table -->
<table class="table table-striped table-bordered table-hover">
    <thead class="table">
        <tr>
            <th>Title</th>
            <th>Status</th>
            <th style="width: 200px;">Actions</th>
        </tr>
    </thead>
    <tbody class="dashboard-posts-container">
        <?php foreach ($data['posts'] as $post): ?>
            <tr class="post-row" data-post-id="<?= $this->e($post['id']) ?>"> <!-- accessed by AJAX -->
                <td class="col-8"><?= $this->e($post['title']) ?></td>
                <td>
                    <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : ($post['status'] === 'draft' ? 'secondary' : 'warning') ?>">
                        <?= $this->e(ucfirst($post['status'])) ?>
                    </span>
                </td>
                <td>
                    <a href="<?= url('dashboard/posts/' . $post['id']) . '/edit' ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                    <button type="button" class="btn btn-sm btn-outline-danger delete-post-btn" data-post-id="<?= $this->e($post['id']) ?>">Delete</button>
                    <a href="<?= url('posts/' . $post['id']) ?>" class="btn btn-sm btn-outline-secondary">View</a>
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