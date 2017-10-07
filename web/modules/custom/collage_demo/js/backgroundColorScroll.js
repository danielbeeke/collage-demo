(function ($) {

  var $backgroundItems = $('.media.background_color.full');

  $backgroundItems.each(function (delta, backgroundItem) {
    backgroundItem.backgroundElement = $('<div class="fixed-background-color"></div>');
    backgroundItem.backgroundElement.css('background-color', $(backgroundItem).attr('data-color'));
    $('body').append(backgroundItem.backgroundElement);
  });

  $(window).on('scroll', function () {
    var scrollTop = $(window).scrollTop();
    var windowHeight = $(window).height();

    $backgroundItems.each(function (delta, backgroundItem) {
      var itemHeight = $(backgroundItem).outerHeight();
      var backgroundStartsOn = Math.round($(backgroundItem).offset().top);
      var pixelsVisibleFromTop = Math.min(Math.max(0, scrollTop - backgroundStartsOn + windowHeight), itemHeight);
      var pixelsVisibleFromBottom = Math.max(0, scrollTop - backgroundStartsOn);
      var pixelsVisible = Math.max(0, pixelsVisibleFromTop - pixelsVisibleFromBottom);
      var percentageVisible = Math.round(100 / itemHeight * pixelsVisible);
      backgroundItem.backgroundElement.css('opacity', percentageVisible / 100);
    });
  });

})(jQuery);
