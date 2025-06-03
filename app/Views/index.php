<!-- Central Layout View -->
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Custom styling-->
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Title-->
    <title><?= $this->e($title) ?></title>
    <!-- CSRF token -->
    <meta name="csrf-token" content="<?= $csrf_token ?>">
</head>

<body>
    <!-- Header -->
    <?php $this->insert('header', ['isLoggedIn' => $isLoggedIn]) ?>

    <?php if (!$this->section('dashboard')): ?>
        <main>
            <!-- Main content -->
            <?= $this->section('content') ?>
        </main>
        <!-- Footer -->
        <footer>
            <p>&copy; <?= date('Y') ?> | Blog App</p>
        </footer>
    <?php else: ?>
        <main>
            <!-- Dashboard content -->
            <?= $this->section('dashboard') ?>
        </main>
    <?php endif ?>

    <!-- JS URLs -->
    <?php $this->insert('urls', []) ?>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS (needs Popper for things like dropdowns or tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <!-- JS Files -->
    <script src="<?= asset('/assets/js/register.js') ?>"></script>
    <script src="<?= asset('/assets/js/login.js') ?>"></script>
    <script src="<?= asset('/assets/js/posts.js') ?>"></script>
    <script src="<?= asset('/assets/js/users.js') ?>"></script>

</body>
</html>