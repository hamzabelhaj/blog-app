<!-- Header Subview-->
<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="<?= url('/') ?>">
                <span class="text-primary">Test</span>Blog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url('/') ?>">Home</a>
                    </li>
                    <!-- Check if user is logged in -->
                    <?php if (!$isLoggedIn): ?>
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
                    <?php else: ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-fill" id="dropdown-icon"></i> <?= $this->e($user['username']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                                <li><a class="dropdown-item" href="<?= url('/dashboard') ?>">Dashboard</a></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= url('/logout') ?>">
                                        <i class="bi bi-box-arrow-left"></i>
                                        Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </nav>
</header>