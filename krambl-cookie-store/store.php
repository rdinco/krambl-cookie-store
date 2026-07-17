<?php
include __DIR__ . "/config.php";
$pageTitle = "Shop";

$category = 0;
$search = "";

if (isset($_GET["category"])) {
    $category = (int) $_GET["category"];
}

if (isset($_GET["q"])) {
    $search = trim($_GET["q"]);
}

$sql = "SELECT products.*, categories.name AS category_name
        FROM products
        INNER JOIN categories ON products.category_id = categories.id
        WHERE products.is_active = 1";

if ($category > 0) {
    $sql = $sql . " AND products.category_id = $category";
}

if ($search != "") {
    $safe_search = mysqli_real_escape_string($conn, $search);
    $sql = $sql . " AND (products.name LIKE '%$safe_search%' OR products.description LIKE '%$safe_search%')";
}

$sql = $sql . " ORDER BY categories.name, products.name";

$products = mysqli_query($conn, $sql);
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");

include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Krambl Menu</span>
    <h1>Shop</h1>
    <p>Pick your favorite freshly baked cookie.</p>
</section>

<section class="shop-layout section">
    <aside class="shop-sidebar">
        <h3>Categories</h3>
        <a href="store.php" class="<?php if ($category == 0) echo "active"; ?>">All</a>

        <?php while ($row = mysqli_fetch_array($categories, MYSQLI_ASSOC)) { ?>
            <a href="?category=<?php echo $row["id"]; ?>"
               class="<?php if ($category == $row["id"]) echo "active"; ?>">
                <?php echo clean($row["name"]); ?>
            </a>
        <?php } ?>

        <hr>

        <form method="get">
            <label>Search</label>
            <input name="q" value="<?php echo clean($search); ?>" placeholder="Cookie flavor">
            <button class="btn small">Filter</button>
        </form>
    </aside>

    <div>
        <div class="shop-top">
            <p><?php echo mysqli_num_rows($products); ?> cookie(s) found</p>
        </div>

        <div class="product-grid">
            <?php while ($product = mysqli_fetch_array($products, MYSQLI_ASSOC)) { ?>
                <article class="product-card">
                    <div class="product-image">
                        <img src="<?php echo clean($product["image"]); ?>" alt="<?php echo clean($product["name"]); ?>">
                    </div>
                    <span><?php echo clean($product["category_name"]); ?> • <?php echo $product["stock"]; ?> left</span>
                    <h3><?php echo clean($product["name"]); ?></h3>
                    <p><?php echo clean($product["description"]); ?></p>

                    <div class="product-bottom">
                        <strong><?php echo money($product["price"]); ?></strong>

                        <?php if ($product["stock"] > 0) { ?>
                            <form method="post" action="cart.php">
                                <input type="hidden" name="product_id" value="<?php echo $product["id"]; ?>">
                                <button class="btn outline small" name="add">Add to Cart</button>
                            </form>
                        <?php } else { ?>
                            <em>Sold out</em>
                        <?php } ?>
                    </div>
                </article>
            <?php } ?>

            <?php if (mysqli_num_rows($products) == 0) { ?>
                <div class="empty">
                    <h2>No cookies matched your search.</h2>
                    <a class="btn" href="store.php">View all cookies</a>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<?php include "includes/footer.php"; ?>
