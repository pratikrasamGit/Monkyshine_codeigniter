
document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const paymentId = urlParams.get('paymentId');
    if (paymentId) {
      const checkoutOptions = {
        checkoutKey: 'live-checkout-key-f37324c734c246e29abe794cbef0c342', // Replace!
        paymentId: paymentId,
        containerId: "checkout-container-div",
      };
      const checkout = new Dibs.Checkout(checkoutOptions);
      checkout.on('payment-completed', function (response) {

        // $.ajax({
        //   type: 'POST',
        //   url: '<?=base_url()?>payment_success',
        //   data:  {paymentId:paymentId},
        //   success: function (data) {
            window.location = '<?=base_url()?>payment/completed';
        //   }
        // });
        
      });
    } else {

      window.location = '<?=base_url()?>payment/404';

      console.log("Expected a paymentId");   // No paymentId provided, 
      // window.location = 'cart.html';         // go back to cart.html
    }
  });