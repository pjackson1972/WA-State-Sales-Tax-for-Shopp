(function ($) {
    checkZip = function ($toCheckout) {
        var $validZip = /^\d{5}(-\d{4})?$/;
        var $shipZip = $('#shipping-postcode').val();

        if (!$validZip.test($shipZip)) {
            $toCheckout.css({
                cursor: 'default',
                opacity: '0.7',
                filter: 'alpha(opacity=70)',
                textDecoration: $($toCheckout).css('text-decoration')
            }).on('mouseover', function () {
                $(this).css({
                    color: $($toCheckout).css('color')
                });
            }).on('click', function (e) {
                e.preventDefault();
            }).removeAttr('href').attr({
                alt: 'You must enter a shipping zipcode before you can proceed to checkout!'
            });
            $('.wataxerror').show();
        }
    };
})(jQuery);
jQuery(document).ready(function ($) {
    var $toCheckout = $('a[href="' + checkoutUrl + '"]');
    $('.ship-estimates').closest('td').prepend('<p class="wataxerror" style="display:none;background: #ffe6e2; padding: 10px; border: 1px solid #7a1829; border-radius: 4px; margin: 0 0 0 10px;">To proceed, enter your zip code and click <span style="font-weight: bold;">Estimate Shipping &amp; Taxes</span></p>');
    checkZip($toCheckout);
    $($toCheckout).on('click', function () {
        checkZip($toCheckout);
    });
});
