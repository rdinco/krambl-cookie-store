<?php
function clean($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

function money($value) {
    return "₱" . number_format($value, 2);
}

function base_path($path = "") {
    return rtrim(BASE_URL, "/") . "/" . ltrim($path, "/");
}

function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

function is_admin() {
    return isset($_SESSION["role"]) && $_SESSION["role"] == "admin";
}

function require_login() {
    if (!is_logged_in()) {
        $_SESSION["message"] = "Please log in first.";
        header("Location: " . base_path("login.php"));
        exit;
    }
}

function require_admin() {
    if (!is_admin()) {
        $_SESSION["message"] = "Administrator access is required.";
        header("Location: " . base_path("login.php"));
        exit;
    }
}

function show_message() {
    if (isset($_SESSION["message"])) {
        echo '<div class="flash success">' . clean($_SESSION["message"]) . '</div>';
        unset($_SESSION["message"]);
    }

    if (isset($_SESSION["error"])) {
        echo '<div class="flash error">' . clean($_SESSION["error"]) . '</div>';
        unset($_SESSION["error"]);
    }
}

function cart_count() {
    $count = 0;

    if (isset($_SESSION["cart"])) {
        foreach ($_SESSION["cart"] as $quantity) {
            $count = $count + $quantity;
        }
    }

    return $count;
}

function audit_log($conn, $action) {
    $user_id = "NULL";

    if (isset($_SESSION["user_id"])) {
        $user_id = (int) $_SESSION["user_id"];
    }

    $safe_action = mysqli_real_escape_string($conn, $action);
    $sql = "INSERT INTO audit_logs (user_id, action) VALUES ($user_id, '$safe_action')";
    mysqli_query($conn, $sql);
}

function get_cart_items($conn) {
    $items = array();

    if (!isset($_SESSION["cart"]) || count($_SESSION["cart"]) == 0) {
        return $items;
    }

    foreach ($_SESSION["cart"] as $product_id => $quantity) {
        $product_id = (int) $product_id;
        $quantity = (int) $quantity;

        $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $product_id AND is_active = 1");
        $product = mysqli_fetch_array($result, MYSQLI_ASSOC);

        if ($product && $quantity > 0) {
            if ($quantity > $product["stock"]) {
                $quantity = $product["stock"];
            }

            if ($quantity > 0) {
                $product["quantity"] = $quantity;
                $product["subtotal"] = $quantity * $product["price"];
                $items[] = $product;
            }
        }
    }

    return $items;
}
?>
