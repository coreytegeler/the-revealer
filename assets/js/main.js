jQuery(function($) {
  return $(function() {
    var $body, $footer, $header, $logo, $nav, $side, $window, fixGrids, fixSide, gatherArticleImages, loadImage, sizeImages, trackScroll;
    $window = $(window);
    $body = $('body');
    $side = $('aside');
    $header = $('header');
    $logo = $('#logo');
    $nav = $('nav');
    $footer = $('footer');
    sizeImages = function() {
      return $('.image.load').each(function() {
        var $image, $img, $parent, image, imageHeight, imageWidth, natHeight, natWidth, ratio, src;
        $image = $(this);
        $img = $image.find('img');
        $image.removeClass('load').addClass('loading');
        if ($img.parents('article')) {
          $parent = $img.parents('article');
        } else {
          $image = $img.parents('.image');
        }
        natWidth = $img.attr('data-width');
        natHeight = $img.attr('data-height');
        if (natWidth && natHeight) {
          ratio = natWidth / natHeight;
          imageWidth = $image.innerWidth();
          imageHeight = imageWidth / ratio;
          $image.css({
            width: imageWidth,
            height: imageHeight
          });
          return loadImage($image);
        } else {
          src = $img.attr('src');
          image = new Image();
          image.onload = function(e) {
            var img;
            img = e.target;
            src = img.src;
            natWidth = img.naturalWidth;
            natHeight = img.naturalHeight;
            ratio = natWidth / natHeight;
            imageWidth = $image.innerWidth();
            imageHeight = imageWidth / ratio;
            $image.css({
              width: imageWidth,
              height: imageHeight
            });
            return loadImage($image);
          };
          return image.src = src;
        }
      });
    };
    loadImage = function($image) {
      var $img, src;
      $img = $image.find('img');
      src = $img.data('src');
      $image.imagesLoaded().progress(function() {
        $image.removeClass('loading').addClass('loaded');
        if ($img.parents('aside').length) {
          return fixSide();
        } else {
          $image.attr('style', '');
          return fixGrids();
        }
      });
      return $img.attr('src', src);
    };
    fixGrids = function($grids, $cells) {
      if (!$grids) {
        $grids = $('.grid');
      }
      return $grids.each(function() {
        var $first, $grid, columnWidth, gutter;
        $grid = $(this);
        if ($cells) {
          $grid.masonry().append($cells).masonry('appended', $cells);
        }
        $first = $grid.find('.cell:eq(0)');
        if (!$first.length) {
          return;
        }
        columnWidth = parseInt($first.css('width'));
        gutter = parseInt($first.css('marginBottom'));
        return $grid.masonry({
          itemSelector: '.cell',
          columnWidth: columnWidth,
          gutter: gutter,
          transitionDuration: 0,
          percentPosition: true
        });
      });
    };
    fixSide = function() {
      var sideHeight, sideScrollHeight, winScroll;
      winScroll = $window.scrollTop();
      sideHeight = $side.innerHeight();
      sideScrollHeight = $side[0].scrollHeight;
      if (sideScrollHeight > sideHeight) {
        return $side.scrollTop(winScroll);
      }
    };
    trackScroll = function() {
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
      progress = (winScroll - headerHeight) * winWidth / pageEnd;
      $('.bar').each(function(i, bar) {
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
      return fixSide();
    };
    gatherArticleImages = function() {
      var $article, $images, img, imgs, j, len, results, src, thumb;
      return;
      if ($body.is('.single')) {
        $images = $side.find('.images');
        $article = $('article');
        imgs = $article.find('.content img');
        results = [];
        for (j = 0, len = imgs.length; j < len; j++) {
          img = imgs[j];
          src = img.src;
          thumb = new Image();
          thumb.onload = function(e) {
            var $image, $img, natHeight, natWidth;
            img = e.target;
            src = img.src;
            $img = $(img);
            natWidth = img.naturalWidth;
            natHeight = img.naturalHeight;
            $img = $('<img/>').attr('src', src).attr('data-width', natWidth).attr('data-height', natHeight);
            $image = $('<div class="image"></div>').append($img).addClass('load').addClass('cell');
            fixGrids($images, $image);
            return sizeImages();
          };
          results.push(thumb.src = src);
        }
        return results;
      }
    };
    $window.on('resize', function() {
      trackScroll();
      fixGrids();
      sizeImages();
      return $nav.css({
        height: $logo.innerHeight()
      });
    }).resize();
    $window.on('scroll', function() {
      return trackScroll();
    });
    return gatherArticleImages();
  });
});
