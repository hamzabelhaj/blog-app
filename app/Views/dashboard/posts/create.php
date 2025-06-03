<!-- Create Post Subview -->
<div class="container mt-5">
    <h2 class="mb-4">Create Post</h2> 

    <form id="create-post-form" method="post">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title"> 
        </div>

        <div class="mb-3">
            <label class="form-label">Body</label>
            <textarea class="form-control" name="body" id="body"></textarea>
        </div>

        <!-- Check if current user has publishing privileges -->
        <?php if ($data['isEditor']): ?>
            <div class="form-check mb-3">
                <input type="checkbox" name="publish" id="publish" class="form-check-input">
                <label for="publish" class="form-check-label">Publish this post</label>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-success">Add</button>
        <a href="<?= url('/dashboard/posts') ?>" class="btn btn-secondary ms-2">Cancel</a>
    </form>

    <div id="create-post-feedback" class="mt-3 text-danger"></div>

</div>

<style>
    #body {
        min-height: 200px;
        max-height: 500px;
    }
</style>

