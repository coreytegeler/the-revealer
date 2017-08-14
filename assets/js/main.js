jQuery(function($) {
  return $(function() {
    var $body, $footer, $header, $window, track;
    $window = $(window);
    $body = $('body');
    $header = $('header');
    $footer = $('footer');
    track = function() {
      var $readable, headerHeight, pageBottom, pageEnd, pageHeight, pageTop, progress, winHeight, winScroll, winWidth;
      $readable = $('.readable');
      if (!$readable.length) {
        return;
      }
      winWidth = $window.innerWidth();
      winHeight = $window.innerHeight();
      winScroll = $window.scrollTop();
      headerHeight = $header.innerHeight();
      pageHeight = $readable.innerHeight();
      pageTop = $readable.position().top;
      pageBottom = pageTop + pageHeight;
      pageEnd = pageBottom - winHeight;
      console.log(winScroll);
      progress = (winScroll - headerHeight) * winWidth / pageEnd;
      return $('.bar').each(function(i, bar) {
        var $bar, headerOpacity, topY;
        $bar = $(this);
        $bar.find('.solid').css({
          width: progress
        });
        headerHeight = $header.innerHeight();
        headerOpacity = winScroll * 1 / headerHeight;
        if ($bar.is('.top')) {
          topY = $bar.position().top;
          if (topY < winScroll) {
            return $bar.addClass('fixed');
          } else {
            return $bar.removeClass('fixed');
          }
        }
      });
    };
    $window.on('resize', track);
    return $window.on('scroll', track);
  });
});
