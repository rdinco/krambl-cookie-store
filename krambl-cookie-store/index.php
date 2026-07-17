<?php
include __DIR__ . "/config.php";
$pageTitle = "Home";

$featured = mysqli_query($conn, "SELECT products.*, categories.name AS category_name
                                FROM products
                                INNER JOIN categories ON products.category_id = categories.id
                                WHERE products.is_active = 1
                                ORDER BY products.id ASC
                                LIMIT 4");

include "includes/header.php";
?>
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Krambl Cookie Store</span>
        <h1>Freshly Baked.<br><em>Made with Love.</em></h1>
        <p>Malambot sa loob, perfect sa bawat kagat. Discover chunky cookies inspired by the sweet flavors of the Philippines.</p>
        <a class="btn" href="store.php">Shop Now</a>
    </div>

    <div class="hero-art">
        <div class="cookie cookie-one"><img src="assets/img/product-classic.svg" alt="Classic cookie"></div>
        <div class="cookie cookie-two"><img src="assets/img/product-tablea.svg" alt="Tablea cookie"></div>
        <div class="cookie cookie-three"><img src="assets/img/product-ube.svg" alt="Ube cookie"></div>
        <div class="crumb c1"></div>
        <div class="crumb c2"></div>
        <div class="crumb c3"></div>
    </div>
</section>

<section class="section favorites">
    <div class="section-head centered">
        <div>
            <span class="eyebrow">Paborito ng Lahat</span>
            <h2>Cookies made for every craving</h2>
        </div>
    </div>

    <div class="product-grid">
        <?php while ($product = mysqli_fetch_array($featured, MYSQLI_ASSOC)) { ?>
            <article class="product-card">
                <div class="product-image">
                    <img src="<?php echo clean($product["image"]); ?>" alt="<?php echo clean($product["name"]); ?>">
                </div>
                <span><?php echo clean($product["category_name"]); ?></span>
                <h3><?php echo clean($product["name"]); ?></h3>
                <p><?php echo clean($product["description"]); ?></p>
                <div class="product-bottom">
                    <strong><?php echo money($product["price"]); ?></strong>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="product_id" value="<?php echo $product["id"]; ?>">
                        <button class="btn outline small" name="add">Add to Cart</button>
                    </form>
                </div>
            </article>
        <?php } ?>
    </div>
</section>

<section class="benefits">
    <article><b>♨</b><div><strong>Freshly Baked</strong><span>Araw-araw na bagong luto</span></div></article>
    <article><b>♡</b><div><strong>Quality Ingredients</strong><span>Pinipili ang gamit</span></div></article>
    <article><b>✦</b><div><strong>Made with Love</strong><span>Gawa sa puso, para sa'yo</span></div></article>
</section>

<section class="story-strip">
    <div>
        <span class="eyebrow">Lasa ng Pinas</span>
        <h2>A modern cookie shop with a Filipino heart.</h2>
    </div>
    <p>From ube and tablea to pandan and mango graham, every Krambl flavor brings a familiar Filipino favorite into one soft, chunky cookie.</p>
</section>
<?php include "includes/footer.php"; ?>
