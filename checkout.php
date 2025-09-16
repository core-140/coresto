<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'db.php';

$session_id = session_id();
$message = "";

// Handle checkout form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $tableno = trim($_POST['tableno']);
    $email   = trim($_POST['email']);
    $cardno  = trim($_POST['cardno']);
    $pin     = trim($_POST['pin']);

    if (!empty($name) && !empty($phone) && !empty($tableno) && !empty($cardno) && !empty($pin)) {
        // Save customer
        $stmt = $conn->prepare("INSERT INTO customers (name, phone) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $phone);
        $stmt->execute();
        $customerid = $stmt->insert_id;
        $stmt->close();

        // Fetch cart items
        $cart = $conn->query("SELECT c.foodid, c.quantity, f.price 
                              FROM cart c JOIN food f ON c.foodid=f.foodid 
                              WHERE c.session_id='$session_id'");

        if ($cart->num_rows > 0) {
            $totalAmount = 0;
            $orderIds = [];

            while ($row = $cart->fetch_assoc()) {
                $foodid   = $row['foodid'];
                $quantity = $row['quantity'];
                $price    = $row['price'];
                $totalAmount += $price * $quantity;

                // Save order
                $stmt = $conn->prepare("INSERT INTO orders (foodid, quantity, tableno, email, status) VALUES (?, ?, ?, ?, 'Pending')");
                $stmt->bind_param("iiis", $foodid, $quantity, $tableno, $email);
                $stmt->execute();
                $orderIds[] = $stmt->insert_id;
                $stmt->close();
            }

            // Save payment (⚠️ demo only — don’t save raw card/pin in real life)
            if (!empty($orderIds)) {
                $firstOrderId = $orderIds[0]; // link to first order
                $stmt = $conn->prepare("INSERT INTO payments (orderid, amount) VALUES (?, ?)");
                $stmt->bind_param("id", $firstOrderId, $totalAmount);
                $stmt->execute();
                $stmt->close();
            }

            // ✅ FIX: Save table number in session for status.php
            $_SESSION['tableno'] = $tableno;

            // Clear cart after placing order
            $conn->query("DELETE FROM cart WHERE session_id='$session_id'");

            $message = "✅ Payment successful & order placed!";
            header("Location: index.php?order_placed=1");
            exit();

        } else {
            $message = "⚠️ Your cart is empty!";
        }
    } else {
        $message = "⚠️ Please fill all required fields.";
    }
}
?>

<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Checkout - CORESTO</title>
    <link rel="stylesheet" href="style.css?v=2.1">
    <style>
        .checkout-page { display: flex; gap: 20px; margin: 30px auto; max-width: 1000px; }
        .order-summary, .checkout-form-box { flex: 1; background: #fff; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .order-summary h3, .checkout-form-box h3 { margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 8px; }
        .order-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .order-table th, .order-table td { padding: 8px; text-align: left; border-bottom: 1px solid #eee; }
        .order-total { font-weight: bold; text-align: right; padding-top: 10px; }
        .checkout-form label { display: block; margin: 10px 0 5px; font-weight: 500; }
        .checkout-form input { width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 6px; }
        .checkout-form button { width: 100%; padding: 12px; background: #ff5200; border: none; color: #fff; font-size: 16px; border-radius: 6px; cursor: pointer; }
        .checkout-form button:hover { background: #e64500; }
        .success-msg { text-align: center; font-weight: bold; color: green; margin-bottom: 20px; }
        .error-msg { text-align: center; font-weight: bold; color: red; margin-bottom: 20px; }
    </style>
</head>
<body>
    <?php include 'navbar.html'; ?>

    <div class="checkout-page">
        <!-- Left side: Order Summary -->
        <div class="order-summary">
            <h3>Your Order</h3>
            <?php
            $cart = $conn->query("SELECT c.foodid, c.quantity, f.foodname, f.price 
                                  FROM cart c JOIN food f ON c.foodid=f.foodid 
                                  WHERE c.session_id='$session_id'");
            if ($cart->num_rows > 0): ?>
                <table class="order-table">
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                    <?php 
                    $grandTotal = 0;
                    while ($row = $cart->fetch_assoc()):
                        $subtotal = $row['price'] * $row['quantity'];
                        $grandTotal += $subtotal;
                    ?>
                    <tr>
                        <td><?= $row['foodname'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td>₹<?= $row['price'] ?></td>
                        <td>₹<?= $subtotal ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
                <div class="order-total">Total: ₹<?= $grandTotal ?></div>
            <?php else: ?>
                <p style="color:red;">⚠️ Your cart is empty!</p>
            <?php endif; ?>
        </div>

        <!-- Right side: Checkout Form -->
        <div class="checkout-form-box">
            <h3>Customer & Payment</h3>

            <?php if (!empty($message)): ?>
                <p class="<?= str_starts_with($message,'✅') ? 'success-msg' : 'error-msg' ?>"><?= $message ?></p>
            <?php endif; ?>

            <form method="POST" class="checkout-form">
            <label for="name">Name*</label>
            <input type="text" id="name" name="name" required pattern="[A-Za-z\s]+" title="Only alphabets allowed">

            <label for="phone">Phone*</label>
            <input type="text" id="phone" name="phone" required pattern="\d{10}" title="Enter 10 digit phone number">

            <label for="tableno">Table Number*</label>
            <input type="number" id="tableno" name="tableno" required min="1" title="Enter table number">

            <label for="email">Email (optional)</label>
            <input type="email" id="email" name="email">

            <label for="cardno">Card Number*</label>
            <input type="text" id="cardno" name="cardno" required pattern="\d{16}" maxlength="16" placeholder="1234 5678 9012 3456" title="Enter 16 digit card number">

            <label for="pin">PIN*</label>
            <input type="password" id="pin" name="pin" required pattern="\d{4}" maxlength="4" placeholder="****" title="Enter 4 digit PIN">

            <button type="submit" name="checkout">Pay & Place Order</button>
        </form>
        </div>
    </div>
</body>
</html>
