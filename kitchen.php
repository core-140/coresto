<?php
session_start();
include 'db.php';

// Approve order → Preparing
if (isset($_GET['approve'])) {
    $orderid = intval($_GET['approve']);
    $conn->query("UPDATE orders SET status='Preparing' WHERE orderid=$orderid");
}

// Mark Ready → Coming to table
if (isset($_GET['ready'])) {
    $orderid = intval($_GET['ready']);
    $conn->query("UPDATE orders SET status='Coming to your table' WHERE orderid=$orderid");
}

// Delete delivered order
if (isset($_GET['delete'])) {
    $orderid = intval($_GET['delete']);
    $conn->query("DELETE FROM orders WHERE orderid=$orderid");
}

// Fetch all orders, sorted by table
$orders = $conn->query("SELECT o.orderid, f.foodname, o.quantity, o.tableno, o.status 
                        FROM orders o 
                        JOIN food f ON o.foodid=f.foodid 
                        ORDER BY o.tableno, o.orderid DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Kitchen Orders</title>
    <link rel="stylesheet" href="style.css?v=2.2">
</head>
<body>
    <h2>Kitchen Orders</h2>

    <?php 
    $currentTable = null;
    while ($row = $orders->fetch_assoc()): 
        if ($currentTable !== $row['tableno']): 
            if ($currentTable !== null) {
                echo "</table><br>";
            }
            $currentTable = $row['tableno'];
            echo "<h3>Table " . htmlspecialchars($currentTable) . "</h3>";
            echo "<table border='1' cellpadding='8' cellspacing='0'>
                    <tr>
                        <th>Order ID</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>";
        endif;
    ?>
        <tr>
            <td><?= $row['orderid'] ?></td>
            <td><?= htmlspecialchars($row['foodname']) ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <?php if ($row['status'] === 'Pending'): ?>
                    <a href="?approve=<?= $row['orderid'] ?>">Approve</a>
                <?php elseif ($row['status'] === 'Preparing'): ?>
                    <a href="?ready=<?= $row['orderid'] ?>">Mark as Ready</a>
                <?php elseif ($row['status'] === 'Coming to your table'): ?>
                    <a href="?delete=<?= $row['orderid'] ?>" onclick="return confirm('Delete this order after delivery?')">🗑 Delete</a>
                <?php else: ?>
                    ✅ Delivered
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>

    </table>
</body>
</html>
