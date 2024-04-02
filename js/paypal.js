// Paypal wants people to use strings. they hate ints and floats
var depositInput = document.querySelector('#deposit');
var deposit = depositInput.value.toString();

depositInput.addEventListener('change', function() {
    deposit = this.value.toString();
});

paypal.Buttons({
    createOrder: function(data, actions) {
        // This function sets up the details of the transaction, including the amount and line item details.
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: deposit
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        // This function captures the funds from the transaction.
        return actions.order.capture().then(function(details) {
            // This function shows a transaction success message to your buyer.
            // Use AJAX to call deposit.php
            $.ajax({
                type: "POST",
                url: "deposit.php",
                data: { deposit: depositInput.value },
                success: function(response) {
                    // You can handle the server response here
                    console.log(response);
                }
            });
        });
    }
}).render('#paypal-button-container'); // This function displays Smart Payment Buttons on your web page.