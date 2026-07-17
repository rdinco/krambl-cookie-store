<?php
include __DIR__ . "/config.php";

$token = "";

if (isset($_GET["token"])) {
    $token = mysqli_real_escape_string($conn, $_GET["token"]);
}

$result = mysqli_query($conn, "SELECT id FROM users WHERE verification_token = '$token' AND is_verified = 0");
$user = mysqli_fetch_array($result, MYSQLI_ASSOC);

if ($user) {
    $user_id = (int) $user["id"];
    mysqli_query($conn, "UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = $user_id");
    $_SESSION["message"] = "Email verified successfully. You may now log in.";
} else {
    $_SESSION["error"] = "The verification link is invalid or has already been used.";
}

header("Location: login.php");
exit;
?>
