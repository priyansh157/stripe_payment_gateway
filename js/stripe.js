// Set your publishable key
var stripe = Stripe('pk_test_51PdjpL2LDHX8NxpiWNBqDVkNNBZyO37bxghhaKwru70GapB3dx28YtNm4JsAHUn8OMw73BIwZqiwYjMdP8G3EdmY006GoLoghH');
var elements = stripe.elements();

// Create an instance of the card Element.
var card = elements.create('card');


card.mount('#card-element');

// Handle real-time validation errors from the card Element.
card.on('change', function(event) {
  var displayError = document.getElementById('card-errors');
  if (event.error) {
    displayError.textContent = event.error.message;
  } else {
    displayError.textContent = '';
  }
});

// Handle form submission.
var form = document.getElementById('payment-form');
form.addEventListener('submit', function(event) {
  event.preventDefault();

  // Get form data
  var formData = new FormData(form);
  var name = formData.get('name');
  var email = formData.get('email');
  var course = formData.get('course');
  var amount = formData.get('amount');

  stripe.createToken(card).then(function(result) {
    if (result.error) {
      // Inform the user if there was an error.
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = result.error.message;
    } else {
      // Check if token is generated
      if (result.token && result.token.id) {
        // Send the token to your server.
        stripeTokenHandler(result.token, name, email, course, amount);
      } else {
        console.error('Token generation failed:', result);
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = 'Token generation failed. Please try again.';
      }
    }
  }).catch(function(error) {
    console.error('Error creating token:', error);
    var errorElement = document.getElementById('card-errors');
    errorElement.textContent = 'An error occurred. Please try again.';
  });
});

// Submit the form with the Stripe token and other form data.
function stripeTokenHandler(token, name, email, course, amount) {
  fetch('stripe_payment.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      stripeToken: token.id,
      name: name,
      email: email,
      course: course,
      amount: amount
    }),
  })
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    if (data.error) {
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = data.error;
    } else if (data.clientSecret) {
      stripe.confirmCardPayment(data.clientSecret, {
        payment_method: {
          card: card,
          billing_details: {
            name: name,
            email: email
          }
        }
      }).then(function(result) {
        if (result.error) {
          // Show error to your customer
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          if (result.paymentIntent.status === 'succeeded') {
            // The payment has been processed!
            window.location.href = 'success.php?pid=' + result.paymentIntent.id;
          }
        }
      });
    } else {
      console.error('Unexpected response:', data);
      var errorElement = document.getElementById('card-errors');
      errorElement.textContent = 'An error occurred. Please try again.';
    }
  })
  .catch(function(error) {
    console.error('Error:', error);
    var errorElement = document.getElementById('card-errors');
    errorElement.textContent = 'An error occurred. Please try again.';
  });
}
