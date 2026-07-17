<?php
include dirname(__DIR__) . "/config.php";
require_admin();

$pageTitle = "Inventory Report";

$products = mysqli_query($conn, "SELECT products.*, categories.name AS category_name
                                 FROM products
                                 INNER JOIN categories ON products.category_id = categories.id
                                 ORDER BY products.stock ASC, products.name");

include "../includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Seller Report</span>
    <h1>Inventory Report</h1>
    <p>Remaining products, prices, and status.</p>
</section>

<section class="section">
    <div class="table-wrap">
        <table>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Price</th>
                <th>Remaining Stock</th>
                <th>Status</th>
            </tr>

            <?php while ($product = mysqli_fetch_array($products, MYSQLI_ASSOC)) { ?>
                <tr class="<?php if ($product["stock"] <= 5) echo "low-stock"; ?>">
                    <td><?php echo clean($product["name"]); ?></td>
                    <td><?php echo clean($product["category_name"]); ?></td>
                    <td><?php echo money($product["price"]); ?></td>
                    <td><?php echo $product["stock"]; ?></td>
                    <td>
                        <?php
                        if ($product["is_active"] == 0) {
                            echo "Hidden";
                        } elseif ($product["stock"] == 0) {
                            echo "Out of Stock";
                        } elseif ($product["stock"] <= 5) {
                            echo "Low Stock";
                        } else {
                            echo "Available";
                        }
                        ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>
<?php include "../includes/footer.php"; ?>
