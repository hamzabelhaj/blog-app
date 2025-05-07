<!-- layout.php -->

<!DOCTYPE html>
<html>
<head>
    <!--Custom styling-->
    <link rel="stylesheet" href="/../../public/assets/css/style.css">
    <!--Title-->
    <title><?= $this->e($title) ?></title>
</head>
<body>

  <!-- Header -->
  <header>
        <h1>My Web Application</h1>
        <nav>
            <a href="/">Home</a> |
            <a href="/users">Users</a>
        </nav>
    </header>

     <main>
        <?= $this->section('content') ?>
    </main>

     <!-- Footer -->
     <footer>
        <p>&copy; <?= date('Y') ?> My App</p>
    </footer>
</body>
</html>