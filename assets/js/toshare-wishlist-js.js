/**
 * Created by ido on 3/28/2017.
 */

(function ($) {
    $(document).ready(function () {

        $('#toshare_add_to_wishlist').on('click', function (e) {
            event.preventDefault();
            $(this).find('i').removeClass('fa-heart-o');
            $(this).find('i').addClass('fa-heart');
            $('i.fa-heart').css({color:'#dd4b39'});
                $.post(document.location.protocol+'//'+document.location.host+'/wp-admin/admin-ajax.php', toshareWishlistAjax, function (response) {
                    
                    }
                );
            // console.log('wishlist button click');
        });
    });
})(jQuery);