$(document).ready(function(){
    $(".add-to-cart").click(function() {
        var productId = $(this).data('product-id');
         
         if (productId == null){
            console.log("what ish good");
         } else{
            console.log("what ish bad");
         }
         console.log(productId);
        $.ajax({
            type: "POST",
            url: "../append_cart.php",
            data: "product_id=" + productId
        });
    });
});

