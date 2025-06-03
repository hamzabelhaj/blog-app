<!-- Dashboard View -->
<?php $this->layout('index', ['title' => 'Dashboard']) ?>

<?php $this->start('dashboard') ?>

<div class="dashboard-container">
    <div class="sidebar">
        <a href="<?= url('/dashboard/profile') ?>">Profile</a>
        <hr>
        <a href="<?= url('/dashboard/posts') ?>">Posts</a>
        <?php if ($data['isAdmin']): ?>
            <hr>
            <a href="<?= url('/dashboard/users') ?>">Users</a>
        <?php endif; ?>
    </div>
    <div class="content">
        <?php if (isset($subView)): ?>
             <?php $this->insert($subView, ['data' => $data]) ?> <!-- To insert subviews(posts/.., users/.., profile/..) -->
        <?php else: ?>
            <h2 class="content-title"> Welcome to your Dashboard <?= $this->e($data['user']['username']) ?>!</h2>
            <div class="links">
                <a href="<?= url('/') ?>" id="home-page-link">Return to Home page</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<style>
    .content .links {
        display: flex;
        justify-content: center;

    }
    .content .links #home-page-link {
        margin: 20px;
        text-decoration: none;
        font-size: 1.2em;
        font-weight: 500;
    }
    .content .links #home-page-link:hover {
        color: black;
    }
</style>

<?php $this->stop('dashboard') ?>