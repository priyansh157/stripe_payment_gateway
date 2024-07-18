<!DOCTYPE html>
<html lang="en">
<head>
  <title>Stripe Payment Form</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://js.stripe.com/v3/"></script>
  <link rel="stylesheet" href="css/stripe.css">
</head>
<body>

<div class="container mt-3">
  <h2>Stripe Payment Form</h2>
  <form id="payment-form">
  
    <div class="mb-3 mt-3">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" placeholder="Enter name" name="name" required>
    </div>
  
    <div class="mb-3 mt-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
    </div>
   
    <div class="mb-3 mt-3">
      <label for="course">Course:</label>
      <input type="text" class="form-control" id="course" placeholder="Enter course" name="course" required>
    </div>
  
    <div class="mb-3 mt-3">
      <label for="amount">Fees Amount:</label>
      <input type="text" class="form-control" id="amount" placeholder="Enter amount" name="amount" required>
    </div>

    <div class="mb-3 mt-3">
      <label for="card-element">Credit or Debit Card:</label>
      <div id="card-element" class="form-control"></div>
      <div id="card-errors" role="alert" style="color: red; margin-top: 10px;"></div>
    </div>
   
    <button type="submit" id="payBtn" class="btn btn-primary">Submit</button>
  </form>
</div>

<script src="js/stripe.js"></script>

</body>
</html>
