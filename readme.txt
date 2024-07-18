# Stripe Payment Integration Application

This application integrates Stripe for payment processing. It includes a payment form, backend handling of payment intents, and webhook handling to update payment statuses in the database.

1. Clone the repository :- https://github.com/priyansh157/stripe_payment_gateway.git

2.Install Composer dependencies:composer install

3.Setup in your local environment: Place the repository folder in your XAMPP or similar server environment.
Stripe Account Setup:

4.If you haven't already, create a Stripe account to obtain your publishable and secret keys for testing.
Configure Keys:

5.Replace the publishable key in js/stripe.js (line 2) with your test publishable key.
Replace the secret key in stripe_payment.php (line 9) with your test secret key.


6.Database Setup: Create a MySQL database named stripe locally.
Import the database schema from the provided stripe.sql file.
Configure Database Connection:
Set the database connection parameters in connection.php according to your local system.

7.Webhook Setup:

1.Go to your Stripe Dashboard.
2.Navigate to Developers > Webhooks.
3.Click on "Add endpoint".
4.Set the URL to your webhook endpoint, e.g., http://yourdomain.com/webhook.php.
5.Select the event types you want to listen to, e.g., payment_intent.succeeded.
6.Click "Add endpoint".
7.Testing with Stripe CLI:

8.Download and install the Stripe CLI from Stripe's official website.
Open your terminal or command prompt.
Login to your Stripe account:stripe login

9.After logging in, run the following command to forward events to your local webhook: stripe listen --forward-to http://your_domain/stripe_payment_gateway/webhook.php

10.Run the Application:Open index.php in your browser using your XAMPP installation path.
Enter payment details to test transactions. You can use Stripe's testing cards for this purpose.

Note:Ensure your local server (like XAMPP) is running and accessible

For any questions or issues, please feel free to contact me. Thank you!