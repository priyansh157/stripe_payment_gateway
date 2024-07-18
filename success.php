<?php
if (isset($_GET['pid'])) {
    $paymentIntentId = $_GET['pid'];
    echo "<h1>Your Payment was Successful!</h1>";
    echo "<p>Payment ID: $paymentIntentId</p>";
    echo '<a href="index.php">Back to Home</a>';
} else {
    echo "<h1>Invalid Payment</h1>";
}
?>
