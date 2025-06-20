<!-- Register View -->
<?php $this->layout('index', ['title' => 'Register']) ?>

<div class="container mt-5">
    <h2 class="mb-4">Create an Account</h2>

    <form id="register-form" method="post">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" id="email">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password">
        </div>

        <div class="mb-3">
            <label for="repeated-password" class="form-label">Repeat Password</label>
            <input type="password" class="form-control" name="repeated-password" id="repeated-password">
        </div>

        <button type="submit" class="btn btn-success">Register</button>
    </form>

    <div id="register-feedback" class="mt-3 text-danger"></div>

    <p class="mt-3">
        Already have an account? <a href="<?= url('/login') ?>">Login here</a>.
    </p>
</div>
