<?php $this->layout('layout', ['title' => 'Login']) ?>

<div class="container mt-5">
    <h2 class="mb-4">Login</h2>

    <form id="login-form" method="post">
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" id="email" >
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" >
        </div>

        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <div id="login-feedback" class="mt-3 text-danger"></div>

    <p class="mt-3">
        Don't have an account? <a href="<?= url('/register') ?>">Register here</a>.
    </p>
</div>



