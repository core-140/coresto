<?php
session_start();
include 'db.php';

$orders = null;
$tableno = "";

// If form submitted, fetch orders by table number
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableno'])) {
    $tableno = intval($_POST['tableno']);
    $orders = $conn->query("SELECT o.orderid, f.foodname, o.quantity, o.status 
                            FROM orders o 
                            JOIN food f ON o.foodid=f.foodid 
                            WHERE o.tableno = $tableno 
                            ORDER BY o.orderid DESC 
                            LIMIT 10");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Status</title>
    <link rel="stylesheet" href="style.css?v=2.1">
    <style>
        .status-box { max-width: 800px; margin: 30px auto; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #4CAF50; color: white; }
        .pending { color: orange; font-weight: bold; }
        .preparing { color: green; font-weight: bold; }
        .coming { color: blue; font-weight: bold; }
        form { margin-bottom: 20px; }
        input[type="number"] { padding: 8px; border: 1px solid #ccc; border-radius: 6px; }
        button { padding: 8px 15px; background: #4CAF50; border: none; color: white; border-radius: 6px; cursor: pointer; }
        button:hover { background: #45a049; }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>

    <div class="status-box">
        <h2>🔍 Search Your Order Status</h2>
        <form method="POST">
            <input type="number" name="tableno" placeholder="Enter your Table Number" required value="<?= htmlspecialchars($tableno) ?>">
            <button type="submit">See Status</button>
        </form>

        <?php if ($orders): ?>
            <h3>Your Order Status (Table <?= htmlspecialchars($tableno) ?>)</h3>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Status</th>
                </tr>
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($row = $orders->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['orderid'] ?></td>
                            <td><?= htmlspecialchars($row['foodname']) ?></td>
                            <td><?= $row['quantity'] ?></td>
                            <td>
                                <?php if ($row['status'] === 'Pending'): ?>
                                    <span class="pending">⏳ Waiting for approval</span>
                                <?php elseif ($row['status'] === 'Preparing'): ?>
                                    <span class="preparing">🍳 Preparing</span>
                                <?php elseif ($row['status'] === 'Coming to your table'): ?>
                                    <span class="coming">🚶 Coming to your table</span>
                                <?php else: ?>
                                    <?= htmlspecialchars($row['status']) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="4">⚠️ No orders found for this table.</td></tr>
                <?php endif; ?>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
