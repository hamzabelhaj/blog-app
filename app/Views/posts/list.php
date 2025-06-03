<!-- Posts Index View -->
<?php $this->layout('index', ['title' => 'Public Posts']) ?>


<div class="container py-4">
    <h1 class="mb-4">Public Posts</h1>

    <nav>
        <ul class="pagination justify-content-center public-pagination">
        <!-- Previous -->
        <?php if ($currentPage <= 1): ?>
            <li class="page-item disabled"><span class="page-link">Previous</span></li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="#" data-page="<?= $currentPage - 1 ?>">Previous</a>
            </li>
        <?php endif; ?>

        <!-- Page numbers -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= ($i === $currentPage) ? 'active' : '' ?>">
                <a class="page-link" href="#" data-page="<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>

        <!-- Next -->
        <?php if ($currentPage >= $totalPages): ?>
            <li class="page-item disabled"><span class="page-link">Next</span></li>
        <?php else: ?>
            <li class="page-item">
                <a class="page-link" href="#" data-page="<?= $currentPage + 1 ?>">Next</a>
            </li>
        <?php endif; ?>
    </ul>
    </nav>

    <div id="public-posts-container">
        <?php foreach ($posts as $post): ?>
            <div class="card mb-4 shadow-sm">
                <div class="card-header text-muted small d-flex justify-content-between">
                    <span>
                        <?= date('m/d/Y', strtotime($post['created_at'])) ?>
                    </span>
                    <span>By <?= $this->e($post['username']) ?></span>
                </div>

                <div class="card-body">
                    <h5 class="card-title"><?= $this->e($post['title']) ?></h5>
                    <p class="card-text">
                        <?= $post['body'] ?>
                    </p>
                    <a href="<?= url('posts/' . $this->e($post['id'])) ?>" class="btn btn-primary btn-sm">Read More</a>

                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>

<style>
    .card-text {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
</style>