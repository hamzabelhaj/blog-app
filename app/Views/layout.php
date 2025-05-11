<!-- layout.php -->

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
</head>

<body>

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
            <div class="container-fluid">
                <a class="navbar-brand fw-bold" href="<?= url('/') ?>">
                    <span class="text-primary">Mizo</span>Blog
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainNavbar">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/') ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/register') ?>">
                                <i class="bi bi-person-plus"></i> Sign Up
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url('/login') ?>">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-fill" id="dropdown-icon"></i> User
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                                <li>
                                    <a class="dropdown-item" href="<?= url('/dashboard') ?>">
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= url('/logout') ?>">
                                        <i class="bi bi-box-arrow-left"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- main content -->
    <main>
        <?= $this->section('content') ?>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?= date('Y') ?> | Blog App</p>
    </footer>

    <script>
        const HOME_URL = "<?= url('/') ?>";
        const LOGIN_URL = "<?= url('login') ?>";
        const REGISTER_URL = "<?= url('register') ?>";
       
    </script>

    <!-- jQuery (required for Bootstrap JS and your own scripts using jQuery) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap JS (needs Popper for things like dropdowns or tooltips) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

    <!-- Your custom JS file (must come last) -->
    <script src="<?= asset('/assets/js/script.js') ?>"></script>

</body>

</html>