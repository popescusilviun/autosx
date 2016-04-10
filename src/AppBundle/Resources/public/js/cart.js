$(function () {
    $(".open-cart").on("click", function (e) {
        e.preventDefault();
        if(!$(this).hasClass('cart-show')) {
            $(".cart-container").show('slide', { direction: 'right' }, 'normal');
            $(this).addClass('cart-show');
        } else {
            $(".cart-container").hide('slide', { direction: 'right' }, 'normal');
            $(this).removeClass('cart-show');
        }
    });
});