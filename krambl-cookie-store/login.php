<?php
include __DIR__ . "/config.php";
$pageTitle = "Login";
$error = "";

if (isset($_POST["login"])) {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $safe_email = mysqli_real_escape_string($conn, $email);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$safe_email' AND is_active = 1");
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if (!$user || !password_verify($password, $user["password"])) {
        $error = "Incorrect email or password.";
    } elseif ($user["is_verified"] == 0) {
        $error = "Please verify your email before logging in.";
    } else {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["complete_name"] = $user["complete_name"];
        $_SESSION["role"] = $user["role"];

        audit_log($conn, "Logged in");

        if ($user["role"] == "admin") {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: store.php");
        }
        exit;
    }
}

include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Welcome Back</span>
    <h1>Login</h1>
</section>

<section class="section form-section">
    <div class="form-card">
        <?php if ($error != "") { ?>
            <div class="flash error"><?php echo clean($error); ?></div>
        <?php } ?>

        <form method="post">
            <label>Email address</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button class="btn" name="login">Login</button>
        </form>
    </div>
</section>
<?php include "includes/footer.php"; ?>
