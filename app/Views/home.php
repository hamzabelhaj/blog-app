<!-- home view -->
<?php $this->layout('index', ['title' => 'Home Page']) ?>

<div class="home-page-content">
    <h1 class="content-title">Welcome to the Blog <?= isset($user['username']) ? $this->e($user['username']) : '' ?></h1>
    <div class="links">
        <a href="<?= url('posts') ?>" id="posts-link">See public posts</a>
        <a href="<?= url('dashboard') ?>" id="dashboard-link">Navigate to Dashboard</a>
    </div>

</div>


<style>
    .home-page-content {
        width: 100%;
        padding: 50px;
    }

    .home-page-content .content-title {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .home-page-content .links {
        display: flex;
        justify-content: center;

    }

    .home-page-content .links #posts-link,
    .home-page-content .links #dashboard-link {
        margin: 20px;
        text-decoration: none;
        font-size: 1.2em;
        font-weight: 500;
    }
    .home-page-content .links #posts-link:hover,
    .home-page-content .links #dashboard-link:hover{
        color: black;
    }
</style>