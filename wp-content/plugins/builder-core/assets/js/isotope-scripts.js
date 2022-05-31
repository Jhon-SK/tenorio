(function ($) {
    "use strict";
    $(document).ready(function(){

        // project start
        $(window).on("load",function() {
            var $container = $('.project-addon');
            $container.isotope({
                filter: '*',
                animationOptions: {
                    duration: 750,
                    easing: 'linear',
                    queue: false
                }
            });
            $('.project-nav li').on("click", function() {
                $('.project-nav li.active').removeClass('active');
                $(this).addClass('active');
                var selector = $(this).attr('data-filter');
                $container.isotope({
                    filter: selector,
                    animationOptions: {
                        duration: 750,
                        easing: 'linear',
                        queue: false
                    }
                });
                return false;
            });
        });
        // project end

    }); //dom ready end

})(jQuery);