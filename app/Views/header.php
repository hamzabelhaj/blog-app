
<header>
    <div class="logo">
        <a href="<?= url() ?>">
            <h1 class="logo-text"><span>Mizo</span>Blog</h1>
        </a>
    </div>

    <a href="#" id="toggle-menu"><i class="bi bi-list toggle-menu"></i></a>

    <ul class="nav-list">
        <li><a href="<?= url() ?>">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Services</a></li>

            <li><a href="<?= url('register') ?>" class="signup"><i class="bi bi-person-plus"></i> Sign Up</a></li>
            <li><a href="<?= url('login') ?>" class="login"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
            <li class="dropdown">
                <a href="#" id="user">
                    <i class="bi bi-person-fill"></i> get('username') ?>
                    <i class="bi bi-chevron-down"></i>
                </a>
                <ul class="dropdown-menu">

                        <li><a href="<?= url('admin/posts') ?>">Dashboard</a></li>
                    <li><a href="<?= url('logout') ?>" class="logout"><i class="bi bi-box-arrow-left"></i> Log Out</a></li>
                </ul>
            </li>

    </ul>
</header>
