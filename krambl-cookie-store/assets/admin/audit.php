<?php
include dirname(__DIR__) . "/config.php";
require_admin();

$pageTitle = "Audit Log Report";

$logs = mysqli_query($conn, "SELECT audit_logs.*, users.complete_name, users.email
                             FROM audit_logs
                             LEFT JOIN users ON audit_logs.user_id = users.id
                             ORDER BY audit_logs.id DESC");

include "../includes/header.php";
?>
<section class="page-hero compact">
    <span class="eyebrow">Seller Report</span>
    <h1>Audit Log Report</h1>
    <p>Activities completed by logged-in users.</p>
</section>

<section class="section">
    <div class="table-wrap">
        <table>
            <tr>
                <th>Date and Time</th>
                <th>User</th>
                <th>Email</th>
                <th>Activity</th>
            </tr>

            <?php while ($log = mysqli_fetch_array($logs, MYSQLI_ASSOC)) { ?>
                <tr>
                    <td><?php echo clean($log["created_at"]); ?></td>
                    <td><?php echo clean($log["complete_name"] ? $log["complete_name"] : "Guest"); ?></td>
                    <td><?php echo clean($log["email"] ? $log["email"] : "-"); ?></td>
                    <td><?php echo clean($log["action"]); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</section>
<?php include "../includes/footer.php"; ?>
