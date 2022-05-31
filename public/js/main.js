(function ($) {
    "use strict";
    $(document).ready(function(){
        
        // dropdown menu on mouse hover start
        if( $(window).width() >= 768 ) {
            $(".dropdown").on('mouseenter', function () {
                $('.nav > .dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).show("400");
                $(this).addClass('open');
            }).on('mouseleave', function () {
                $('.nav > .dropdown-menu', this).not('.in .dropdown-menu').stop(true, true).hide("400");
                $(this).removeClass('open');
            });
        }
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 1) {
                $('#back-to-top').addClass('reveal');
                $('#header').addClass('navbar-fixed-top');
            }
            else {
                $('#back-to-top').removeClass('reveal');
                $('#header').removeClass('navbar-fixed-top');
            }
        });

        // dropdown menu on mouse hover end

        //animated counter start
        $('.count-box').appear(function(){
            var datacount = $(this).attr('data-count');
            $(this).find('.timer').delay(6000).countTo({
                from: 0,
                to: datacount,
                speed: 5000,
                refreshInterval: 50,
            });
        });
        // animated counter end

        // back-to-top start
        $('#back-to-top').on('click', function(){
            $("html, body").animate({scrollTop: 0}, 1000);
            return false;
        });
        // back-to-top end

    }); //dom ready end

})(jQuery);