<?php
include "connection.php"; // Assuming this file contains your database connection
require 'vendor/autoload.php'; // Add this line to autoload Composer dependencies

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$stripe = new \Stripe\StripeClient('sk_test_51PdjpL2LDHX8NxpiNzMxkRb7o8aAOMVT6tI1Kr9SENj0evcOypGyTED72RbWNif7ajIzrZMCQnKaQqsQTyb5KZYA00GR7mebIY');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the raw POST data
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    $token = $data['stripeToken'];
    $name = $data['name'];
    $email = $data['email'];
    $course = $data['course'];
    $price = $data['amount'];

    // Check if amount is valid
    if ($price < 0.5) { // For example, minimum amount is $0.50 (50 cents)
        echo json_encode(['error' => 'Amount must be at least 50 cents.']);
        exit();
    }

    try {
        // Create a PaymentIntent
        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => $price * 100, // convert to cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => [
                'name' => $name,
                'email' => $email,
                'course' => $course
            ],
        ]);

        // Retrieve payment intent details
        $paymentIntentId = $paymentIntent->id;
        $clientSecret = $paymentIntent->client_secret;

        // Insert into database with initial status
        $sql = "INSERT INTO stripe_payment (name, email, coursename, fees, status, paymentid, added_date) 
                VALUES ('$name', '$email', '$course', '$price', 'pending', '$paymentIntentId', NOW())";
    
        if (mysqli_query($con, $sql)) {
            echo json_encode(['clientSecret' => $clientSecret]);
        } else {
            echo json_encode(['error' => "Error: " . $sql . "<br>" . mysqli_error($con)]);
        }
    } catch (Exception $e) {
        // Handle exceptions
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Invalid Request']);
}
?>
