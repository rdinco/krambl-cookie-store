<?php
include __DIR__ . "/config.php";
$pageTitle = "Cart";

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = array();
}

if (isset($_POST["add"])) {
    $product_id = (int) $_POST["product_id"];
    $result = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id AND is_active = 1");
    $product = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($product && $product["stock"] > 0) {
        if (isset($_SESSION["cart"][$product_id])) {
            $_SESSION["cart"][$product_id] = $_SESSION["cart"][$product_id] + 1;
        } else {
            $_SESSION["cart"][$product_id] = 1;
        }

        if ($_SESSION["cart"][$product_id] > $product["stock"]) {
            $_SESSION["cart"][$product_id] = $product["stock"];
        }

        $_SESSION["message"] = "Product added to cart.";
    }

    header("Location: cart.php");
    exit;
}

if (isset($_POST["update"])) {
    foreach ($_POST["quantity"] as $product_id => $quantity) {
        $product_id = (int) $product_id;
        $quantity = (int) $quantity;

        if ($quantity <= 0) {
            unset($_SESSION["cart"][$product_id]);
        } else {
            $result = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id");
            $product = mysqli_fetch_array($result, MYSQLI_ASSOC);

            if ($product) {
                if ($quantity > $product["stock"]) {
                    $quantity = $product["stock"];
                }
                $_SESSION["cart"][$product_id] = $quantity;
            }
        }
    }

    $_SESSION["message"] = "Cart updated.";
    header("Location: cart.php");
    exit;
}

if (isset($_GET["remove"])) {
    $product_id = (int) $_GET["remove"];
    unset($_SESSION["cart"][$product_id]);
    $_SESSION["message"] = "Product removed from cart.";
    header("Location: cart.php");
    exit;
}

$items = get_cart_items($conn);
$total = 0;

include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Your Order</span>
    <h1>Shopping Cart</h1>
</section>

<section class="section">
    <?php if (count($items) == 0) { ?>
        <div class="empty">
            <h2>Your cart is empty.</h2>
            <a class="btn" href="store.php">Shop Cookies</a>
        </div>
    <?php } else { ?>
        <form method="post">
            <div class="table-wrap">
                <table>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>

                    <?php foreach ($items as $item) {
                        $total = $total + $item["subtotal"];
                    ?>
                        <tr>
                            <td><?php echo clean($item["name"]); ?></td>
                            <td><?php echo money($item["price"]); ?></td>
                            <td>
                                <input type="number"
                                       name="quantity[<?php echo $item["id"]; ?>]"
                                       value="<?php echo $item["quantity"]; ?>"
                                       min="0"
                                       max="<?php echo $item["stock"]; ?>">
                            </td>
                            <td><?php echo money($item["subtotal"]); ?></td>
                            <td><a href="?remove=<?php echo $item["id"]; ?>">Remove</a></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>

            <div class="cart-actions">
                <strong>Total: <?php echo money($total); ?></strong>
                <button class="btn outline" name="update">Update Cart</button>
                <a class="btn" href="checkout.php">Checkout</a>
            </div>
        </form>
    <?php } ?>
</section>
<?php include "includes/footer.php"; ?>
