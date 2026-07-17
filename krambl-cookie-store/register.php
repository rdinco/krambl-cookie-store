<?php
include __DIR__ . "/config.php";
$pageTitle = "Register";
$error = "";
$verification_link = "";

if (isset($_POST["register"])) {
    $name = trim($_POST["complete_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $address = trim($_POST["address"]);
    $contact = trim($_POST["contact"]);

    if ($name == "" || $email == "" || $password == "" || $address == "" || $contact == "") {
        $error = "Please complete all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif ($password != $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must contain at least 6 characters.";
    } else {
        $safe_email = mysqli_real_escape_string($conn, $email);
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$safe_email'");

        if (mysqli_num_rows($check) > 0) {
            $error = "That email address is already registered.";
        } else {
            $safe_name = mysqli_real_escape_string($conn, $name);
            $safe_address = mysqli_real_escape_string($conn, $address);
            $safe_contact = mysqli_real_escape_string($conn, $contact);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = md5($email . time());

            $sql = "INSERT INTO users
                    (complete_name, email, password, address, contact, role, is_verified, verification_token, is_active)
                    VALUES
                    ('$safe_name', '$safe_email', '$hashed_password', '$safe_address', '$safe_contact',
                     'buyer', 0, '$token', 1)";

            if (mysqli_query($conn, $sql)) {
                $new_user_id = mysqli_insert_id($conn);
                $_SESSION["user_id"] = $new_user_id;
                audit_log($conn, "Registered a buyer account");
                unset($_SESSION["user_id"]);

                $verification_link = BASE_URL . "/verify.php?token=" . $token;
                $subject = "Verify your Krambl account";
                $message = "Hello $name, please verify your account using this link: $verification_link";
                @mail($email, $subject, $message);

                $_SESSION["message"] = "Registration successful. Please verify your email.";
            } else {
                $error = "Registration was not completed. Please try again.";
            }
        }
    }
}

include "includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Create Account</span>
    <h1>Register</h1>
</section>

<section class="section form-section">
    <div class="form-card">
        <?php if ($error != "") { ?>
            <div class="flash error"><?php echo clean($error); ?></div>
        <?php } ?>

        <?php if ($verification_link != "" && LOCAL_TESTING == true) { ?>
            <div class="flash success">
                Local testing link:
                <a href="<?php echo clean($verification_link); ?>">Verify account</a>
            </div>
        <?php } ?>

        <form method="post">
            <label>Complete name</label>
            <input type="text" name="complete_name" required>

            <label>Email address</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm password</label>
            <input type="password" name="confirm_password" required>

            <label>Complete address</label>
            <textarea name="address" required></textarea>

            <label>Contact number</label>
            <input type="text" name="contact" required>

            <button class="btn" name="register">Register</button>
        </form>
    </div>
</section>
<?php include "includes/footer.php"; ?>
