<script src="https://js.stripe.com/v3/"></script>
<script src="https://checkout.stripe.com/checkout.js"></script>

<div id="payment-request-button">
 <button id="customButton" class="btn balance-add">Add Fund</button>>
</div>
<script>
   
  var handler = StripeCheckout.configure({
  key: 'pk_test_51M9qbcC3DIsz8es6Env2YYqsqnRpIiHCS7BSWE2pcRRJxMwxYrcRQYj3psqsOafTgARlQnpQbYlq4oSV1QjwAX7I00QFHFYePp',
  image: 'https://booking.isidelbeachpark.com/assets/img/isidel.png',
  locale: 'auto',
  token: function(token) {
      console.log(token);
  }
});
document.getElementById('customButton').addEventListener('click', function(e) {
  handler.open({
    name: 'Isidel Beach Park',
    description: '',
    amount: 2000
  });
  e.preventDefault();
});

window.addEventListener('popstate', function() {
  handler.close();
});
    
</script>