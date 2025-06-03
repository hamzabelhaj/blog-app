<!-- Edit Post Subview  -->
<div class="container mt-5">
    <h2 class="mb-4">Edit Post</h2>

    <form id="edit-post-form" method="post" data-post-id="<?= $this->e($data['post']['id']) ?>">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?= $this->e($data['post']['title']) ?? '' ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Body</label>
            <textarea class="form-control" name="body" id="body"><?= $this->e($data['post']['body']) ?? '' ?></textarea>
        </div>

        <!-- Check if current user has publishing privileges -->
        <?php if ($data['isEditor']): ?>
            <div class="form-check mb-3">
                <input type="checkbox" name="publish" id="publish" class="form-check-input" <?= $data['isPublished'] ? 'checked' : '' ?> ?>
                <label for="publish" class="form-check-label">Publish this post</label>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= url('/dashboard/posts') ?>" class="btn btn-secondary ms-2">Cancel</a>
        <div id="update-post-feedback" class="mt-3 text-danger"></div>

    </form>

    <div id="login-feedback" class="mt-3 text-danger"></div>

</div>