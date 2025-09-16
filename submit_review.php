<?php
include 'db.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $foodid = intval($_POST['foodid']);
    $email = trim($_POST['email']);
    $review_text = trim($_POST['review_text']);

    if(!empty($foodid) && !empty($email) && !empty($review_text)){
        $stmt = $conn->prepare("INSERT INTO feedback (foodid, email, review_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $foodid, $email, $review_text);
        if($stmt->execute()){
            echo "Review submitted!";
        } else {
            echo "Failed to submit review: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>
