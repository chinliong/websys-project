$(document).ready(function(){
    $(".add-to-cart").click(function() {
        var productId = $(this).data('product-id');
         console.log("what is good");
        $.ajax({
            type: "POST",
            url: "../append_cart.php",
            data: "product_id=" + productId
        });
    });
});