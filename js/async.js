$(document).ready(function(){
    $(".add-to-cart").click(function() {
        var productId = $(this).data('product-id');
        $.ajax({
            type: "POST",
            url: "../append_cart.php",
            data: "product_id=" + productId
        });
    });
});


let addToCartBtn = document.querySelector(".add-to-cart");

let opt = {
  initialText: "Add to Cart",
  textOnClick: "Item Added",
  interval: 1000,
};

let setAddToCartText = () => {
  addToCartBtn.innerHTML = opt.textOnClick;
  let reinit = () => {
    addToCartBtn.innerHTML = opt.initialText;
  };
  setTimeout(reinit, opt.interval);
};

addToCartBtn.addEventListener("click", setAddToCartText);