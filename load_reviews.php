<?php
include 'db.php';

$foodid = intval($_GET['foodid']);

// Fetch reviews in descending order
$result = $conn->query("SELECT * FROM feedback WHERE foodid=$foodid ORDER BY feedbackid DESC");

if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $email_safe = htmlspecialchars($row['email']);
        $review_safe = htmlspecialchars($row['review_text']);
        echo "<p><span class='review-email'>{$email_safe}</span>: {$review_safe}</p>";
    }
} else {
    echo "<p>No reviews yet. Be the first!</p>";
}
?>
