<?php
include dirname(__DIR__) . "/config.php";
require_admin();

$pageTitle = "Manage Admin Users";
$error = "";
$edit_user = null;

if (isset($_POST["add_user"])) {
    $name = trim($_POST["complete_name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($name == "" || $email == "" || $password == "") {
        $error = "Please complete all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $safe_name = mysqli_real_escape_string($conn, $name);
        $safe_email = mysqli_real_escape_string($conn, $email);
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$safe_email'");

        if (mysqli_num_rows($check) > 0) {
            $error = "Email address already exists.";
        } else {
            $sql = "INSERT INTO users
                    (complete_name, email, password, address, contact, role, is_verified, is_active)
                    VALUES
                    ('$safe_name', '$safe_email', '$hashed_password', 'Krambl Office', 'N/A', 'admin', 1, 1)";

            if (mysqli_query($conn, $sql)) {
                audit_log($conn, "Added administrator: $email");
                $_SESSION["message"] = "Administrator added.";
                header("Location: users.php");
                exit;
            }
        }
    }
}

if (isset($_POST["update_user"])) {
    $id = (int) $_POST["id"];
    $name = trim($_POST["complete_name"]);
    $email = trim($_POST["email"]);
    $active = 0;

    if (isset($_POST["is_active"])) {
        $active = 1;
    }

    if ($name == "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid name and email address.";
    } else {
        $safe_name = mysqli_real_escape_string($conn, $name);
        $safe_email = mysqli_real_escape_string($conn, $email);

        mysqli_query($conn, "UPDATE users
                            SET complete_name = '$safe_name',
                                email = '$safe_email',
                                is_active = $active
                            WHERE id = $id AND role = 'admin'");

        audit_log($conn, "Updated administrator number $id");
        $_SESSION["message"] = "Administrator updated.";
        header("Location: users.php");
        exit;
    }
}

if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id = $id AND role = 'admin'");
    $edit_user = mysqli_fetch_array($result, MYSQLI_ASSOC);
}

if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];

    if ($id != $_SESSION["user_id"]) {
        mysqli_query($conn, "DELETE FROM users WHERE id = $id AND role = 'admin'");
        audit_log($conn, "Deleted administrator number $id");
        $_SESSION["message"] = "Administrator deleted.";
    } else {
        $_SESSION["error"] = "You cannot delete your own account.";
    }

    header("Location: users.php");
    exit;
}

$users = mysqli_query($conn, "SELECT * FROM users WHERE role = 'admin' ORDER BY id DESC");

include "../includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Seller Part</span>
    <h1>Manage Admin Users</h1>
</section>

<section class="section admin-two-column">
    <div class="form-card">
        <h2><?php if ($edit_user) echo "Edit Administrator"; else echo "Add Administrator"; ?></h2>

        <?php if ($error != "") { ?>
            <div class="flash error"><?php echo clean($error); ?></div>
        <?php } ?>

        <?php if ($edit_user) { ?>
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $edit_user["id"]; ?>">

                <label>Complete name</label>
                <input type="text" name="complete_name" value="<?php echo clean($edit_user["complete_name"]); ?>" required>

                <label>Email address</label>
                <input type="email" name="email" value="<?php echo clean($edit_user["email"]); ?>" required>

                <label>
                    <input type="checkbox" name="is_active" <?php if ($edit_user["is_active"] == 1) echo "checked"; ?>>
                    Active account
                </label>

                <button class="btn" name="update_user">Update</button>
                <a class="btn outline" href="users.php">Cancel</a>
            </form>
        <?php } else { ?>
            <form method="post">
                <label>Complete name</label>
                <input type="text" name="complete_name" required>

                <label>Email address</label>
                <input type="email" name="email" required>

                <label>Password</label>
                <input type="password" name="password" required>

                <button class="btn" name="add_user">Add Administrator</button>
            </form>
        <?php } ?>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = mysqli_fetch_array($users, MYSQLI_ASSOC)) { ?>
                <tr>
                    <td><?php echo clean($user["complete_name"]); ?></td>
                    <td><?php echo clean($user["email"]); ?></td>
                    <td><?php if ($user["is_active"] == 1) echo "Active"; else echo "Inactive"; ?></td>
                    <td>
                        <a href="?edit=<?php echo $user["id"]; ?>">Edit</a>
                        |
                        <a href="?delete=<?php echo $user["id"]; ?>" onclick="return confirm('Delete this administrator?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>
<?php include "../includes/footer.php"; ?>
