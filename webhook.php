<?php
require_once('vendor/autoload.php');

$stripe = new \Stripe\StripeClient('sk_test_51PdjpL2LDHX8NxpiNzMxkRb7o8aAOMVT6tI1Kr9SENj0evcOypGyTED72RbWNif7ajIzrZMCQnKaQqsQTyb5KZYA00GR7mebIY');
$endpoint_secret = 'whsec_066b176c98dcf1110d7cec6fb4d0d818aafd4d4bd141622001b6b15b80a253a5';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
    error_log("Webhook verified successfully");
} catch (\UnexpectedValueException $e) {
    error_log("Invalid payload");
    http_response_code(400);
    exit();
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    error_log("Invalid signature");
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event['type']) {
    case 'payment_intent.succeeded':
        $paymentIntent = $event['data']['object'];
        $paymentIntentId = $paymentIntent['id'];
        $status = $paymentIntent['status'];

        // Update payment status in your database
        include "connection.php";
        $sql = "UPDATE stripe_payment SET status='$status' WHERE paymentid='$paymentIntentId'";
        if (mysqli_query($con, $sql)) {
            error_log("PaymentIntent $paymentIntentId marked as succeeded.");
        } else {
            error_log("Error updating status for PaymentIntent $paymentIntentId: " . mysqli_error($con));
        }

        // Optionally, send a confirmation email to the user
        break;
    default:
        error_log('Received unknown event type ' . $event['type']);
}

http_response_code(200);
?>
