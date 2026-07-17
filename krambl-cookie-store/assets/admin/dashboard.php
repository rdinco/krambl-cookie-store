<?php
include dirname(__DIR__) . "/config.php";
require_admin();

$pageTitle = "Admin Dashboard";

$product_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$product_count = mysqli_fetch_array($product_result, MYSQLI_ASSOC);

$user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role = 'admin'");
$user_count = mysqli_fetch_array($user_result, MYSQLI_ASSOC);

$stock_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products WHERE stock <= 5");
$stock_count = mysqli_fetch_array($stock_result, MYSQLI_ASSOC);

include "../includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Seller Part</span>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo clean($_SESSION["complete_name"]); ?>.</p>
</section>

<section class="section">
    <div class="stat-grid">
        <article><strong><?php echo $product_count["total"]; ?></strong><span>Products</span></article>
        <article><strong><?php echo $user_count["total"]; ?></strong><span>Administrators</span></article>
        <article><strong><?php echo $stock_count["total"]; ?></strong><span>Low-stock Products</span></article>
    </div>

    <div class="admin-links">
        <a class="btn" href="users.php">Manage Admin Users</a>
        <a class="btn" href="products.php">Manage Products</a>
        <a class="btn" href="inventory.php">Inventory Report</a>
        <a class="btn" href="audit.php">Audit Log Report</a>
    </div>
</section>
<?php include "../includes/footer.php"; ?>
