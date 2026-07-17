<?php
include dirname(__DIR__) . "/config.php";
require_admin();

$pageTitle = "Manage Products";
$error = "";
$edit_product = null;

if (isset($_POST["add_product"])) {
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $stock = (int) $_POST["stock"];
    $category_id = (int) $_POST["category_id"];
    $image = trim($_POST["image"]);

    if ($name == "" || $price < 0 || $stock < 0 || $category_id == 0) {
        $error = "Please enter valid product information.";
    } else {
        $safe_name = mysqli_real_escape_string($conn, $name);
        $safe_description = mysqli_real_escape_string($conn, $description);
        $safe_image = mysqli_real_escape_string($conn, $image);

        $sql = "INSERT INTO products
                (category_id, name, description, price, stock, image, is_active)
                VALUES
                ($category_id, '$safe_name', '$safe_description', $price, $stock, '$safe_image', 1)";

        if (mysqli_query($conn, $sql)) {
            audit_log($conn, "Added product: $name");
            $_SESSION["message"] = "Product added.";
            header("Location: products.php");
            exit;
        }
    }
}

if (isset($_POST["update_product"])) {
    $id = (int) $_POST["id"];
    $name = trim($_POST["name"]);
    $description = trim($_POST["description"]);
    $price = (float) $_POST["price"];
    $stock = (int) $_POST["stock"];
    $category_id = (int) $_POST["category_id"];
    $image = trim($_POST["image"]);
    $active = 0;

    if (isset($_POST["is_active"])) {
        $active = 1;
    }

    $safe_name = mysqli_real_escape_string($conn, $name);
    $safe_description = mysqli_real_escape_string($conn, $description);
    $safe_image = mysqli_real_escape_string($conn, $image);

    mysqli_query($conn, "UPDATE products
                        SET category_id = $category_id,
                            name = '$safe_name',
                            description = '$safe_description',
                            price = $price,
                            stock = $stock,
                            image = '$safe_image',
                            is_active = $active
                        WHERE id = $id");

    audit_log($conn, "Updated product number $id");
    $_SESSION["message"] = "Product updated.";
    header("Location: products.php");
    exit;
}

if (isset($_GET["edit"])) {
    $id = (int) $_GET["edit"];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE id = $id");
    $edit_product = mysqli_fetch_array($result, MYSQLI_ASSOC);
}

if (isset($_GET["delete"])) {
    $id = (int) $_GET["delete"];
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    audit_log($conn, "Deleted product number $id");
    $_SESSION["message"] = "Product deleted.";
    header("Location: products.php");
    exit;
}

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name");
$products = mysqli_query($conn, "SELECT products.*, categories.name AS category_name
                                 FROM products
                                 INNER JOIN categories ON products.category_id = categories.id
                                 ORDER BY products.id DESC");

include "../includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Seller Part</span>
    <h1>Manage Products</h1>
</section>

<section class="section admin-two-column">
    <div class="form-card">
        <h2><?php if ($edit_product) echo "Edit Product"; else echo "Add Product"; ?></h2>

        <?php if ($error != "") { ?>
            <div class="flash error"><?php echo clean($error); ?></div>
        <?php } ?>

        <form method="post">
            <?php if ($edit_product) { ?>
                <input type="hidden" name="id" value="<?php echo $edit_product["id"]; ?>">
            <?php } ?>

            <label>Product name</label>
            <input type="text" name="name"
                   value="<?php if ($edit_product) echo clean($edit_product["name"]); ?>" required>

            <label>Category</label>
            <select name="category_id" required>
                <option value="">Select category</option>
                <?php while ($category = mysqli_fetch_array($categories, MYSQLI_ASSOC)) { ?>
                    <option value="<?php echo $category["id"]; ?>"
                        <?php if ($edit_product && $edit_product["category_id"] == $category["id"]) echo "selected"; ?>>
                        <?php echo clean($category["name"]); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Description</label>
            <textarea name="description" required><?php if ($edit_product) echo clean($edit_product["description"]); ?></textarea>

            <label>Price</label>
            <input type="number" name="price" step="0.01"
                   value="<?php if ($edit_product) echo $edit_product["price"]; ?>" required>

            <label>Stock</label>
            <input type="number" name="stock"
                   value="<?php if ($edit_product) echo $edit_product["stock"]; ?>" required>

            <label>Image path</label>
            <input type="text" name="image"
                   value="<?php if ($edit_product) echo clean($edit_product["image"]); else echo "assets/img/product-classic.svg"; ?>"
                   required>

            <?php if ($edit_product) { ?>
                <label>
                    <input type="checkbox" name="is_active" <?php if ($edit_product["is_active"] == 1) echo "checked"; ?>>
                    Show product in store
                </label>

                <button class="btn" name="update_product">Update Product</button>
                <a class="btn outline" href="products.php">Cancel</a>
            <?php } else { ?>
                <button class="btn" name="add_product">Add Product</button>
            <?php } ?>
        </form>
    </div>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>

            <?php while ($product = mysqli_fetch_array($products, MYSQLI_ASSOC)) { ?>
                <tr>
                    <td><?php echo clean($product["name"]); ?></td>
                    <td><?php echo clean($product["category_name"]); ?></td>
                    <td><?php echo money($product["price"]); ?></td>
                    <td><?php echo $product["stock"]; ?></td>
                    <td>
                        <a href="?edit=<?php echo $product["id"]; ?>">Edit</a>
                        |
                        <a href="?delete=<?php echo $product["id"]; ?>" onclick="return confirm('Delete this product?')">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>
<?php include "../includes/footer.php"; ?>
