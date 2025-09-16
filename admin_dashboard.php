<?php
session_start();
include 'db.php';

// ---------- Redirect if not logged in ----------
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// ---------- Handle Add Food ----------
if (isset($_POST['add_food'])) {
    $foodname = $conn->real_escape_string($_POST['foodname']);
    $price = floatval($_POST['price']);
    $category = $conn->real_escape_string($_POST['category']);
    $description = $conn->real_escape_string($_POST['description']);
    $image = $conn->real_escape_string($_POST['image']);
    $rating = floatval($_POST['rating']);

    $conn->query("INSERT INTO food (foodname, price, category, description, image, rating) VALUES ('$foodname', $price, '$category', '$description', '$image', $rating)");
    header("Location: admin_dashboard.php");
    exit;
}

// ---------- Fetch Summary Counts ----------
$totalOrders = $conn->query("SELECT COUNT(*) AS c FROM orders")->fetch_assoc()['c'];
$pendingOrders = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='Pending'")->fetch_assoc()['c'];
$completedOrders = $conn->query("SELECT COUNT(*) AS c FROM orders WHERE status='Delivered'")->fetch_assoc()['c'];
$totalReviews = $conn->query("SELECT COUNT(*) AS c FROM feedback")->fetch_assoc()['c'];
$totalFood = $conn->query("SELECT COUNT(*) AS c FROM food")->fetch_assoc()['c'];
$totalCustomers = $conn->query("SELECT COUNT(DISTINCT email) AS c FROM orders")->fetch_assoc()['c'];
$totalPayments = $conn->query("
    SELECT SUM(o.quantity * f.price) AS c
    FROM orders o
    JOIN food f ON o.foodid = f.foodid
")->fetch_assoc()['c'];

// ---------- Handle Review Delete ----------
if (isset($_GET['del_review'])) {
    $id = intval($_GET['del_review']);
    $conn->query("DELETE FROM feedback WHERE feedbackid=$id");
    header("Location: admin_dashboard.php");
    exit;
}

// ---------- Handle Order Status Update ----------
if (isset($_GET['status']) && isset($_GET['orderid'])) {
    $orderid = intval($_GET['orderid']);
    $status = $conn->real_escape_string($_GET['status']);
    $conn->query("UPDATE orders SET status='$status' WHERE orderid=$orderid");
    header("Location: admin_dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - CORESTO</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; margin: 0; }
        .navbar { background: #2c3e50; color: #fff; padding: 15px; font-size: 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: #fff; text-decoration: none; background: #e74c3c; padding: 6px 12px; border-radius: 5px; }
        .navbar a:hover { background: #c0392b; }
        .container { padding: 20px; }
        .cards { display: flex; gap: 20px; margin-bottom: 20px; flex-wrap: wrap; }
        .card { flex: 1; padding: 15px; background: #fff; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        table { width: 100%; border-collapse: collapse; background: #fff; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background: #27ae60; color: white; }
        .btn { padding: 6px 12px; border: none; cursor: pointer; border-radius: 5px; }
        .btn-pending { background: orange; color: white; }
        .btn-preparing { background: #3498db; color: white; }
        .btn-coming { background: purple; color: white; }
        .btn-delivered { background: green; color: white; }
        .btn-delete { background: red; color: white; }
        .add-food-form { background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .add-food-form input, .add-food-form textarea { width: 100%; padding: 8px; margin: 5px 0; border-radius: 5px; border: 1px solid #ccc; }
        .add-food-form button { background: #27ae60; color: #fff; padding: 10px 15px; border: none; border-radius: 5px; cursor: pointer; }
        .add-food-form button:hover { background: #219150; }
    </style>
</head>
<body>

<div class="navbar">
    <span>🍴 CORESTO - Admin Dashboard | Welcome <?= htmlspecialchars($_SESSION['admin']); ?></span>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <!-- Summary Cards -->
    <div class="cards">
        <div class="card"><h3>Total Orders</h3><p><?= $totalOrders ?></p></div>
        <div class="card"><h3>Pending Orders</h3><p><?= $pendingOrders ?></p></div>
        <div class="card"><h3>Delivered Orders</h3><p><?= $completedOrders ?></p></div>
        <div class="card"><h3>Total Reviews</h3><p><?= $totalReviews ?></p></div>
        <div class="card"><h3>Total Food Items</h3><p><?= $totalFood ?></p></div>
        <div class="card"><h3>Total Customers</h3><p><?= $totalCustomers ?></p></div>
        <div class="card"><h3>Total Payments</h3><p>₹<?= $totalPayments ?></p></div>
    </div>

    <!-- Add Food Item Form -->
    <div class="add-food-form">
        <h2>Add Food Item</h2>
        <form method="post" action="">
            <input type="text" name="foodname" placeholder="Food Name" required>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="text" name="category" placeholder="Category" required>
            <textarea name="description" placeholder="Description"></textarea>
            <input type="text" name="image" placeholder="Image URL">
            <input type="number" step="0.1" min="0" max="5" name="rating" placeholder="Rating (0-5)">
            <button type="submit" name="add_food">Add Food</button>
        </form>
    </div>

    <!-- Orders Table -->
    <h2>Orders</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Table No</th>
            <th>Food Item</th>
            <th>Quantity</th>
            <th>Email</th>
            <th>Status</th>
            <th>Change Status</th>
        </tr>
        <?php
        $orders = $conn->query("SELECT o.*, f.foodname 
                                FROM orders o 
                                JOIN food f ON o.foodid = f.foodid 
                                ORDER BY o.orderid DESC");
        while ($row = $orders->fetch_assoc()) {
            echo "<tr>
                <td>{$row['orderid']}</td>
                <td>{$row['tableno']}</td>
                <td>".htmlspecialchars($row['foodname'])."</td>
                <td>{$row['quantity']}</td>
                <td>".htmlspecialchars($row['email'])."</td>
                <td>{$row['status']}</td>
                <td>
                    <a href='?orderid={$row['orderid']}&status=Pending' class='btn btn-pending'>Pending</a>
                    <a href='?orderid={$row['orderid']}&status=Preparing' class='btn btn-preparing'>Preparing</a>
                    <a href='?orderid={$row['orderid']}&status=Coming to table' class='btn btn-coming'>Coming</a>
                    <a href='?orderid={$row['orderid']}&status=Delivered' class='btn btn-delivered'>Delivered</a>
                </td>
            </tr>";
        }
        ?>
    </table>

    <!-- Reviews Table -->
    <h2>Reviews</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Food</th>
            <th>Email</th>
            <th>Review</th>
            <th>Action</th>
        </tr>
        <?php
        $reviews = $conn->query("SELECT r.*, f.foodname 
                                 FROM feedback r 
                                 JOIN food f ON r.foodid = f.foodid 
                                 ORDER BY r.feedbackid DESC");
        while ($rev = $reviews->fetch_assoc()) {
            echo "<tr>
                <td>{$rev['feedbackid']}</td>
                <td>".htmlspecialchars($rev['foodname'])."</td>
                <td>".htmlspecialchars($rev['email'])."</td>
                <td>".htmlspecialchars($rev['review_text'])."</td>
                <td><a href='?del_review={$rev['feedbackid']}' class='btn btn-delete'>Delete</a></td>
            </tr>";
        }
        ?>
    </table>

    <!-- Food Items Table -->
    <h2>Food Items</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Description</th>
        </tr>
        <?php
        $foods = $conn->query("SELECT * FROM food ORDER BY foodid DESC");
        while ($food = $foods->fetch_assoc()) {
            echo "<tr>
                <td>{$food['foodid']}</td>
                <td>".htmlspecialchars($food['foodname'])."</td>
                <td>₹{$food['price']}</td>
                <td>".htmlspecialchars($food['description'])."</td>
            </tr>";
        }
        ?>
    </table>

    <!-- Customers Table -->
    <h2>Customers</h2>
    <table>
        <tr>
            <th>Email</th>
            <th>Total Orders</th>
        </tr>
        <?php
        $customers = $conn->query("SELECT email, COUNT(*) as orders_count FROM orders GROUP BY email ORDER BY email");
        while ($cust = $customers->fetch_assoc()) {
            echo "<tr>
                <td>".htmlspecialchars($cust['email'])."</td>
                <td>{$cust['orders_count']}</td>
            </tr>";
        }
        ?>
    </table>

    <!-- Payments Table -->
    <h2>Payments</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Email</th>
            <th>Amount</th>
            <th>Status</th>
        </tr>
        <?php
        $payments = $conn->query("
            SELECT o.orderid, o.email, o.quantity * f.price AS amount, o.status
            FROM orders o
            JOIN food f ON o.foodid = f.foodid
            ORDER BY o.orderid DESC
        ");
        while ($pay = $payments->fetch_assoc()) {
            echo "<tr>
                <td>{$pay['orderid']}</td>
                <td>".htmlspecialchars($pay['email'])."</td>
                <td>₹{$pay['amount']}</td>
                <td>{$pay['status']}</td>
            </tr>";
        }
        ?>
    </table>

</div>
</body>
</html>
