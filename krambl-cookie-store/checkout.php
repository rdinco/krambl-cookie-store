<?php
include __DIR__ . "/config.php";
require_login();

$pageTitle = "Checkout";
$items = get_cart_items($conn);

if (count($items) == 0) {
    $_SESSION["error"] = "Your cart is empty.";
    header("Location: store.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];
$result = mysqli_query($conn, "SELECT * FROM users WHERE id = $user_id");
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

$total = 0;
foreach ($items as $item) {
    $total = $total + $item["subtotal"];
}

include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Almost There</span>
    <h1>Checkout</h1>
</section>

<section class="section form-section">
    <div class="form-card">
        <form method="post" action="payment.php">
            <label>Complete name</label>
            <input type="text" name="customer_name" value="<?php echo clean($user["complete_name"]); ?>" required>

            <label>Delivery address</label>
            <textarea name="address" required><?php echo clean($user["address"]); ?></textarea>

            <label>Contact number</label>
            <input type="text" name="contact" value="<?php echo clean($user["contact"]); ?>" required>

            <p><strong>Order total: <?php echo money($total); ?></strong></p>

            <button class="btn" name="continue_payment">Continue to Payment</button>
        </form>
    </div>
</section>
<?php include "includes/footer.php"; ?>
