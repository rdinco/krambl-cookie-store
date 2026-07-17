<?php
include __DIR__ . "/config.php";
require_login();

$pageTitle = "Payment";
$items = get_cart_items($conn);

if (count($items) == 0) {
    $_SESSION["error"] = "Your cart is empty.";
    header("Location: store.php");
    exit;
}

if (isset($_POST["continue_payment"])) {
    $_SESSION["checkout_name"] = trim($_POST["customer_name"]);
    $_SESSION["checkout_address"] = trim($_POST["address"]);
    $_SESSION["checkout_contact"] = trim($_POST["contact"]);
}

if (!isset($_SESSION["checkout_name"])) {
    header("Location: checkout.php");
    exit;
}

$total = 0;
foreach ($items as $item) {
    $total = $total + $item["subtotal"];
}

if (isset($_POST["place_order"])) {
    $payment_method = mysqli_real_escape_string($conn, $_POST["payment_method"]);
    $name = mysqli_real_escape_string($conn, $_SESSION["checkout_name"]);
    $address = mysqli_real_escape_string($conn, $_SESSION["checkout_address"]);
    $contact = mysqli_real_escape_string($conn, $_SESSION["checkout_contact"]);
    $user_id = (int) $_SESSION["user_id"];

    $enough_stock = true;

    foreach ($items as $item) {
        $product_id = (int) $item["id"];
        $quantity = (int) $item["quantity"];

        $stock_result = mysqli_query($conn, "SELECT stock FROM products WHERE id = $product_id");
        $stock_row = mysqli_fetch_array($stock_result, MYSQLI_ASSOC);

        if (!$stock_row || $stock_row["stock"] < $quantity) {
            $enough_stock = false;
        }
    }

    if ($enough_stock == true) {
        $sql = "INSERT INTO orders
                (user_id, customer_name, address, contact, payment_method, total, status)
                VALUES
                ($user_id, '$name', '$address', '$contact', '$payment_method', $total, 'Pending')";

        if (mysqli_query($conn, $sql)) {
            $order_id = mysqli_insert_id($conn);

            foreach ($items as $item) {
                $product_id = (int) $item["id"];
                $quantity = (int) $item["quantity"];
                $price = (float) $item["price"];

                mysqli_query($conn, "INSERT INTO order_items
                                    (order_id, product_id, quantity, price)
                                    VALUES
                                    ($order_id, $product_id, $quantity, $price)");

                mysqli_query($conn, "UPDATE products
                                    SET stock = stock - $quantity
                                    WHERE id = $product_id");
            }

            audit_log($conn, "Placed order number $order_id");

            $_SESSION["cart"] = array();
            unset($_SESSION["checkout_name"]);
            unset($_SESSION["checkout_address"]);
            unset($_SESSION["checkout_contact"]);

            $_SESSION["message"] = "Order placed successfully. Order number: $order_id";
            header("Location: store.php");
            exit;
        }
    } else {
        $_SESSION["error"] = "One or more products no longer have enough stock.";
        header("Location: cart.php");
        exit;
    }
}

include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Demo Payment</span>
    <h1>Payment</h1>
    <p>No real payment will be processed.</p>
</section>

<section class="section form-section">
    <div class="form-card">
        <p><strong>Total: <?php echo money($total); ?></strong></p>

        <form method="post">
            <label>Payment method</label>
            <select name="payment_method" required>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="GCash Demo">GCash (Demo)</option>
                <option value="Card Demo">Card (Demo)</option>
            </select>

            <button class="btn" name="place_order">Place Order</button>
        </form>
    </div>
</section>
<?php include "includes/footer.php"; ?>
