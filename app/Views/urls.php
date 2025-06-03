<!-- URls -->
<script>
    //CSRF Token
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    //Home page
    const HOME_URL = "<?= url('/') ?>";

    //Register
    const REGISTER_URL = "<?= url('register') ?>";

    //Login
    const LOGIN_URL = "<?= url('login') ?>";

    //Logout
    const LOGOUT_URL = "<?= url('logout') ?>";

    //Posts
    const POSTS_URL = "<?= url('posts') ?>";
    const DASHBOARD_POSTS_URL = "<?= url('dashboard/posts') ?>";
    const STORE_POST_URL = "<?= url('/dashboard/posts/store') ?>";
    const DASHBOARD_POSTS_PAGE_URL = "<?= url('dashboard/posts/page') ?>";
    const POSTS_PAGE_URL = "<?= url('posts/page') ?>";

    //Profile
    const PROFILE_URL = "<?= url('dashboard/profile') ?>";
    const UPDATE_PROFILE_URL = "<?= url('/dashboard/profile/update') ?>";
    const DELETE_PROFILE_URL = "<?= url('dashboard/profile/delete') ?>";

    //Users
    const USERS_URL = "<?= url('dashboard/users') ?>";
    const STORE_USER_URL = "<?= url('dashboard/users/store') ?>";
    const USERS_PAGE_URL = "<?= url('dashboard/users/page') ?>";
</script>