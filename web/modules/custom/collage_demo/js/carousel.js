(function ($) {

    var $carousels = $('.media.carousel.full .owl-carousel');

    $carousels.each(function (delta, carousel) {
      var $carousel = $(carousel);

      var carouselOptions = {
        nav: true,
        items: 1
      };

      $carousel.owlCarousel(carouselOptions);
    });

})(jQuery);
