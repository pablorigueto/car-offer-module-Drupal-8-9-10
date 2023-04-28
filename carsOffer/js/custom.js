(function ($, Drupal, drupalSettings) {
    Drupal.behaviors.cookieAvailability = {
        attach(context, drupalSettings) {
            /**
             * Slider show.
             */
            $(document).ready(function(){
                $('.slider-cars').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    adaptiveHeight: true
                });
            });
        }
    };
})(jQuery, Drupal, drupalSettings);
