jQuery(function($) {
  var $body, $carousels, closeCarousel, createCarousel, fixCarouselHeight, openCarousel, resizeCarousel, setupCarousel, transitionEnd;
  $body = $('body');
  transitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd';
  $carousels = $('.carousel');
  createCarousel = function() {
    var $article, $carousel, $imgs, $slides;
    $article = $('article.readable');
    $carousel = $('.carousel');
    $slides = $carousel.find('.slides');
    if ($article.length) {
      $imgs = $article.find('img');
      $imgs.each(function(i, img) {
        var $newImg, $slide, $wrap, fullImg, fullImgSrc, imgExt, imgSrc, imgSrcEnd;
        $newImg = $(img).clone();
        $(img).attr('data-index', i);
        $slide = $('<div class="slide"></div>');
        $wrap = $('<div class="wrap"></div>');
        $slide.attr('data-index', i);
        $wrap.append($newImg);
        $slide.append($wrap);
        $slides.append($slide);
        imgSrc = $newImg.attr('src');
        if (!imgSrc) {
          return;
        }
        imgSrcEnd = imgSrc.substring(imgSrc.lastIndexOf('-') + 1);
        if (!isNaN(parseInt(imgSrcEnd))) {
          imgExt = imgSrc.substring(imgSrc.lastIndexOf('.') + 1);
          fullImgSrc = imgSrc.replace('-' + imgSrcEnd, '') + '.' + imgExt;
          fullImg = new Image;
          fullImg.onload = function(e) {
            $(img).attr('data-full', fullImgSrc);
            return $newImg.attr('src', fullImgSrc);
          };
          return fullImg.src = fullImgSrc;
        }
      });
      if ($imgs.length > 1) {
        $carousel.addClass('slidable');
      }
      return setupCarousel();
    }
  };
  openCarousel = function(e) {
    var $carousel, $img, $this, $thisSlide, fullSrc, href, index, isImage, src;
    $carousel = $('.carousel');
    if ($carousel.is('.opening')) {
      e.preventDefault();
      return;
    }
    $carousel.addClass('opening');
    $this = $(this);
    href = $this.attr('href');
    src = $this.attr('src');
    if (href) {
      $img = $this.find('img');
      if ($img.length) {
        isImage = true;
      }
    } else if (src) {
      $img = $this;
      isImage = true;
    } else {
      isImage = false;
    }
    index = $img.attr('data-index');
    if (isImage) {
      if (fullSrc = $img.attr('data-full')) {
        src = fullSrc;
      }
      if ($thisSlide = $carousel.find('.slide[data-index="' + index + '"]')) {
        $carousel.slide(null, $thisSlide);
        $carousel.addClass('show');
        e.preventDefault();
      }
    }
    return setTimeout(function() {
      return $carousel.removeClass('opening');
    }, 500);
  };
  closeCarousel = function(e) {
    var $carousel;
    $carousel = $(this).parents('.carousel');
    return $carousel.removeClass('show');
  };
  resizeCarousel = function() {
    var windowWidth;
    windowWidth = $(window).innerWidth();
    return $carousels.each(function() {
      var $carousel, $currentSlide, $slides, $slidesWrapper, currentIndex, slidesLength;
      $carousel = $(this);
      $slides = $carousel.find('.slide');
      slidesLength = $slides.length;
      $slidesWrapper = $carousel.find('.slides');
      $currentSlide = $carousel.find('.slide.current');
      currentIndex = $currentSlide.index();
      $slidesWrapper.addClass('static');
      return $slides.each(function(i, slide) {
        var $slide, image, imageUrl;
        imageUrl = $(slide).find('.image').css('backgroundImage');
        if (imageUrl) {
          imageUrl = imageUrl.replace('url(', '').replace(')', '').replace(/"/g, '');
        } else {
          return;
        }
        image = new Image;
        $slide = $(this);
        image.onload = function() {
          var height, ratio, width;
          width = image.width;
          height = image.height;
          ratio = width / height;
          if (width >= height) {
            $slide.addClass('landscape');
          } else {
            $slide.addClass('portrait');
          }
          if (!parseInt($slide.css('width'))) {
            $slide.css({
              width: 'calc(100%/' + slidesLength + ')'
            });
          }
          if ($slide.is('.current')) {
            return fixCarouselHeight($slide);
          }
        };
        return image.src = imageUrl;
      });
    });
  };
  fixCarouselHeight = function($slide) {
    var $caption, $carousel, captionHeight;
    $carousel = $slide.parents('#carousel');
    $caption = $slide.find('.caption');
    return captionHeight = $caption.innerHeight();
  };
  setupCarousel = function() {
    $('#carousel').each(function(i, carousel) {
      return $(carousel).imagesLoaded(function() {
        return $(carousel).addClass('loaded');
      });
    });
    $('body').on('mouseenter', '#carousel.loaded.slidable .arrow:not(.no)', function() {
      var $arrow, $carousel, direction;
      $arrow = $(this);
      direction = $arrow.attr('data-direction');
      $carousel = $arrow.parents('#carousel');
      return $carousel.attr('data-direction', direction);
    });
    $('body').on('mouseleave', '#carousel.loaded.slidable .arrow', function() {
      var $arrow, $carousel;
      $arrow = $(this);
      $carousel = $arrow.parents('#carousel');
      return $carousel.attr('data-direction', '');
    });
    $('body').on('click', '#carousel.loaded.slidable .arrow:not(.no)', function() {
      var $arrow, $carousel, direction;
      $arrow = $(this);
      $carousel = $arrow.parents('#carousel');
      direction = $arrow.attr('data-direction');
      return $carousel.slide(direction);
    });
    return resizeCarousel();
  };
  $.fn.slide = function(direction, go) {
    var $arrow, $carousel, $currentSlide, $firstSlide, $lastSlide, $nextSlide, $slidesWrapper, currentIndex, windowWidth;
    $carousel = $(this);
    $arrow = $carousel.find('.arrow.' + direction);
    windowWidth = $(window).innerWidth();
    $slidesWrapper = $carousel.find('.slides');
    $currentSlide = $carousel.find('.slide.current');
    currentIndex = $currentSlide.index();
    $firstSlide = $carousel.find('.slide').first();
    $lastSlide = $carousel.find('.slide').last();
    $slidesWrapper.removeClass('static');
    if (go) {
      $nextSlide = $(go);
    } else if (direction === 'left') {
      $nextSlide = $currentSlide.prev('.slide');
      if (!$nextSlide.length) {
        $nextSlide = $lastSlide;
      }
    } else if (direction === 'right') {
      $nextSlide = $currentSlide.next('.slide');
      if (!$nextSlide.length) {
        $nextSlide = $firstSlide;
      }
    }
    fixCarouselHeight($nextSlide);
    $arrow.addClass('no');
    $slidesWrapper.stop();
    $currentSlide.removeClass('current');
    $nextSlide.addClass('current');
    return $arrow.removeClass('no');
  };
  $('body').on('click', 'article.readable a, article.readable img', openCarousel);
  $('body').on('click', '#carousel .close', closeCarousel);
  return $(function() {
    if ($carousels.length) {
      return createCarousel();
    }
  });
});
