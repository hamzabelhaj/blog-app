<!-- Display Single Post View -->
<?php $this->layout('index', ['title' => 'Single Post']) ?>

<div class="container py-4">
  <div class="card shadow-sm">
    <div class="card-header text-muted small d-flex justify-content-between">
      <span><?= date('F j, Y', strtotime($post['created_at'])) ?></span>
      <span>By <?= $this->e($post['username']) ?></span>
    </div>

    <div class="card-body">
      <h2 class="card-title mb-3"><?= $this->e($post['title']) ?></h2>
      <p class="card-text" style="white-space: pre-line;">
        <?= ($post['body']) ?> <!-- already sanitized -->
      </p>
      <a href="<?=url('posts')?>" class="btn btn-secondary btn-sm mt-3">Back to All Posts</a>
    </div>
  </div>
</div>