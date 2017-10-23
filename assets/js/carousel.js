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
        $slide = $('<div class="slide"></div>');
        $wrap = $('<div class="wrap"></div>');
        $wrap.append($newImg);
        $slide.append($wrap);
        $slides.append($slide);
        imgSrc = $newImg.attr('src');
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
    var $carousel, $img, $this, $thisSlide, $thisSlideImg, fullSrc, href, isImage, src;
    $carousel = $('.carousel');
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
    if (isImage) {
      if (fullSrc = $img.attr('data-full')) {
        src = fullSrc;
      }
      if ($thisSlideImg = $carousel.find('img[src="' + src + '"]')) {
        $thisSlide = $thisSlideImg.parents('.slide');
        $carousel.slide(null, $thisSlide);
        $carousel.addClass('show');
        return e.preventDefault();
      }
    }
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
      var $carousel, $currentSlide, $slides, $slidesWrapper, currentIndex, left, slidesLength;
      $carousel = $(this);
      $slides = $carousel.find('.slide');
      slidesLength = $slides.length;
      $slidesWrapper = $carousel.find('.slides');
      $currentSlide = $carousel.find('.slide.current');
      currentIndex = $currentSlide.index();
      left = -1 * currentIndex * windowWidth;
      $slidesWrapper.addClass('static');
      $slidesWrapper.css({
        width: slidesLength * windowWidth,
        x: left
      });
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
    var $caption, $carousel, captionHeight, height, minHeight;
    $carousel = $slide.parents('#carousel');
    $caption = $slide.find('.caption');
    captionHeight = $caption.innerHeight();
    minHeight = $carousel.css('content').replace(/['"]+/g, '');
    height = 'calc(' + minHeight + ' + ' + captionHeight + 'px)';
    return $carousel.transition({
      'height': height
    }, 200, 'out');
  };
  setupCarousel = function() {
    $('#carousel').each(function(i, carousel) {
      $(this).find('.slide:first-child').addClass('current');
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
    var $arrow, $carousel, $currentSlide, $firstSlide, $lastSlide, $nextSlide, $slidesWrapper, currentIndex, delay, left, windowWidth;
    $carousel = $(this);
    $arrow = $carousel.find('.arrow.' + direction);
    windowWidth = $(window).innerWidth();
    $slidesWrapper = $carousel.find('.slides');
    $currentSlide = $carousel.find('.slide.current');
    currentIndex = $currentSlide.index();
    $firstSlide = $carousel.find('.slide').first();
    $lastSlide = $carousel.find('.slide').last();
    left = parseInt($slidesWrapper.css('left'));
    $slidesWrapper.removeClass('static');
    switch (direction) {
      case 'left':
        $nextSlide = $currentSlide.prev('.slide');
        left += windowWidth;
        break;
      case 'right':
        $nextSlide = $currentSlide.next('.slide');
        left -= windowWidth;
        break;
      default:
        $nextSlide = $(go);
        if ($currentSlide < $nextSlide) {
          direction = 'left';
        }
        if ($currentSlide > $nextSlide) {
          direction = 'right';
        }
    }
    if (!$nextSlide.length) {
      switch (direction) {
        case 'left':
          $lastSlide.insertBefore($firstSlide);
          $nextSlide = $lastSlide;
          $slidesWrapper.addClass('static');
          currentIndex = $currentSlide.index();
          left = -1 * currentIndex * windowWidth;
          $slidesWrapper.css({
            x: left
          });
          left += windowWidth;
          break;
        case 'right':
          $firstSlide.insertAfter($lastSlide);
          $nextSlide = $firstSlide;
          $slidesWrapper.addClass('static');
          currentIndex = $currentSlide.index();
          left = -1 * currentIndex * windowWidth;
          $slidesWrapper.css({
            x: left
          });
          left -= windowWidth;
          break;
        default:
          return;
      }
      delay = 100;
    } else {
      delay = 0;
    }
    fixCarouselHeight($nextSlide);
    return setTimeout((function() {
      $slidesWrapper.removeClass('static');
      $arrow.addClass('no');
      $slidesWrapper.stop();
      $currentSlide.removeClass('current');
      $nextSlide.addClass('current');
      return $slidesWrapper.transition({
        x: left
      }, function() {
        switch (direction) {
          case 'left':
            $lastSlide.insertBefore($firstSlide);
            break;
          case 'right':
            $firstSlide.insertAfter($lastSlide);
        }
        resizeCarousel();
        return $arrow.removeClass('no');
      });
    }), delay);
  };
  $('body').on('click', 'article.readable a, article.readable img', openCarousel);
  $('body').on('click', '#carousel .close', closeCarousel);
  $(function() {
    if ($carousels.length) {
      return createCarousel();
    }
  });
  return $(window).resize(function() {
    return setupCarousel();
  });
});
