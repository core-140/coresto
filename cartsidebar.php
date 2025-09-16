<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'db.php';

$session_id = session_id();

// ----------------- ADD ITEM -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $foodid   = intval($_POST['foodid']);
    $quantity = intval($_POST['quantity'] ?? 1);

    $check = $conn->prepare("SELECT * FROM cart WHERE foodid=? AND session_id=?");
    $check->bind_param("is", $foodid, $session_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $update = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE foodid=? AND session_id=?");
        $update->bind_param("iis", $quantity, $foodid, $session_id);
        $update->execute();
    } else {
        $insert = $conn->prepare("INSERT INTO cart (foodid, quantity, session_id) VALUES (?, ?, ?)");
        $insert->bind_param("iis", $foodid, $quantity, $session_id);
        $insert->execute();
    }
    exit("success");
}

// ----------------- REMOVE ITEM -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove') {
    $remove_id = intval($_POST['cartid']);

    $check = $conn->prepare("SELECT quantity FROM cart WHERE cartid=? AND session_id=?");
    $check->bind_param("is", $remove_id, $session_id);
    $check->execute();
    $result = $check->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['quantity'] > 1) {
            $update = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE cartid=? AND session_id=?");
            $update->bind_param("is", $remove_id, $session_id);
            $update->execute();
        } else {
            $delete = $conn->prepare("DELETE FROM cart WHERE cartid=? AND session_id=?");
            $delete->bind_param("is", $remove_id, $session_id);
            $delete->execute();
        }
    }
    exit("success");
}

// ----------------- FETCH ITEMS -----------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'load') {
    $items = $conn->query("SELECT c.cartid, f.foodname, f.price, c.quantity 
                           FROM cart c 
                           JOIN food f ON c.foodid=f.foodid 
                           WHERE c.session_id='$session_id'");

    if ($items->num_rows > 0) {
        echo "<table>
                <tr><th>Item</th><th>Qty</th><th>Price</th><th></th></tr>";
        $grandTotal = 0;
        while ($row = $items->fetch_assoc()) {
            $total = $row['price'] * $row['quantity'];
            $grandTotal += $total;
            echo "<tr>
                    <td>".htmlspecialchars($row['foodname'])."</td>
                    <td>{$row['quantity']}</td>
                    <td>₹{$total}</td>
                    <td><button class='removeBtn' data-id='{$row['cartid']}'>Remove</button></td>
                  </tr>";
        }
        echo "</table>
              <div class='grand-total'>Total: ₹{$grandTotal}</div>
              <div class='cart-actions'>
                <a href='checkout.php' class='btn'>Checkout</a>
              </div>";
    } else {
        echo "<p class='empty-msg'>Your cart is empty.</p>";
    }
    exit;
}
?>

<!-- Cart Toggle Button -->
<button class="cart-btn" onclick="toggleCart()">Cart</button>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-header">
        <h3>Your Cart</h3>
        <button class="close-btn" onclick="toggleCart()">×</button>
    </div>
    <div id="cartContent" class="cart-body">
        <!-- AJAX content loads here -->
    </div>
</div>

<!-- jQuery + AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function toggleCart() {
    document.getElementById("cartSidebar").classList.toggle("open");
}

function loadCart() {
    $.post("cartsidebar.php", { action: "load" }, function(data) {
        $("#cartContent").html(data);
    });
}

$(document).ready(function() {
    loadCart();

    // Add to cart
    $(".addToCart").click(function() {
        let foodid = $(this).data("id");
        let quantity = 1;

        $.post("cartsidebar.php", { action: "add", foodid: foodid, quantity: quantity }, function(res) {
            if (res === "success") {
                loadCart();
                $("#cartSidebar").addClass("open");
            }
        });
    });

    // Remove from cart
    $(document).on("click", ".removeBtn", function() {
        let cartid = $(this).data("id");
        $.post("cartsidebar.php", { action: "remove", cartid: cartid }, function(res) {
            if (res === "success") {
                loadCart();
            }
        });
    });
});
</script>
