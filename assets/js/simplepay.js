jQuery( function( $ ) {

    var simplepay_submit = false;

    /* Pay Page Form */
    jQuery( '#simplepay-payment-button' ).click( function() {
        return simplePayFormHandler();
    });

    jQuery( '#simplepay_form form#order_review' ).submit( function() {
        return simplePayFormHandler();
    });

    function simplePayFormHandler() {

        if ( simplepay_submit ) {
            simplepay_submit = false;
            return true;
        }

        var $form            = $( 'form#payment-form, form#order_review' ),
            wc_simplepay_token  = $form.find( 'input.wc_simplepay_token' );

        wc_simplepay_token.val( '' );

        var simplepay_callback = function( token ) {

            $form.append( '<input type="hidden" class="wc_simplepay_token" name="wc_simplepay_token" value="' + token + '"/>' );
            $form.append( '<input type="hidden" class="wc_simplepay_token" name="wc_simplepay_order_id" value="' + wc_simplepay_params.order_id + '"/>' );
            simplepay_submit = true;

            $form.submit();

            $( this.el ).block({
                message: null,
                timeout: 4000,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

        };

        var wcSimplepayHandler = SimplePay.configure({
            token: simplepay_callback,
            key: wc_simplepay_params.key,
            image: wc_simplepay_params.logo,
            onClose: function() {
                $( this.el ).unblock();
            }
        });

        wcSimplepayHandler.open( SimplePay.CHECKOUT,
        {
            email: wc_simplepay_params.email,
            address: wc_simplepay_params.address,
            city: wc_simplepay_params.city,
            country: wc_simplepay_params.country,
            amount: wc_simplepay_params.amount,
            description: wc_simplepay_params.description,
            currency: wc_simplepay_params.currency
        } );

        return false;

    }

} );


