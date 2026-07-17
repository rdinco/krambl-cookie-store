<?php
if (!isset($pageTitle)) {
    $pageTitle = "Krambl";
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo clean($pageTitle); ?> | Krambl Cookies</title>
    <link rel="stylesheet" href="<?php echo base_path("assets/css/style.css"); ?>">
    <script defer src="<?php echo base_path("assets/js/app.js"); ?>"></script>
</head>
<body>
<header class="site-header">
    <a class="brand" href="<?php echo base_path("index.php"); ?>">
        <img src="<?php echo base_path("assets/img/logo.svg"); ?>" alt="Krambl Cookies logo">
    </a>

    <button class="nav-toggle" type="button" aria-label="Toggle navigation">☰</button>

    <nav class="main-nav">
        <a href="<?php echo base_path("index.php"); ?>">Home</a>
        <a href="<?php echo base_path("store.php"); ?>">Shop</a>
        <a href="<?php echo base_path("about.php"); ?>">About</a>

        <?php if (is_admin()) { ?>
            <a href="<?php echo base_path("admin/dashboard.php"); ?>">Seller Admin</a>
        <?php } ?>

        <?php if (is_logged_in()) { ?>
            <a href="<?php echo base_path("logout.php"); ?>">Logout</a>
        <?php } else { ?>
            <a href="<?php echo base_path("login.php"); ?>">Login</a>
            <a href="<?php echo base_path("register.php"); ?>">Register</a>
        <?php } ?>

        <a class="cart-link" href="<?php echo base_path("cart.php"); ?>">
            Cart (<?php echo cart_count(); ?>)
        </a>
    </nav>
</header>
<main>
<?php show_message(); ?>
