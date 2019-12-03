jQuery(function($) {
  return $(function() {
    var $alert, $body, $carousel, $footer, $header, $headers, $logo, $main, $nav, $popup, $seeker, $side, $window, $wrapper, animateText, animateTexts, assetsUrl, clickCarouselArrow, closeAlert, closeCarousel, closePopup, closeSeeker, createCarousel, dur, fixCarouselHeight, fixHeader, fixLoops, fixSide, fixToggler, hoverCell, isMobile, lastSideScroll, lastWeek, loadImage, now, openCarousel, popupObj, queryMore, resizeCarousel, setupArticle, shareWindow, sideScroll, siteUrl, sizeImages, themeUrl, toggleSeeker, toggleToggler, trackScroll, transitionEnd, transport, watchForHover;
    $window = $(window);
    $body = $('body');
    $wrapper = $('#wrapper');
    $side = $('aside');
    $header = $('header');
    $logo = $('#logo');
    $headers = $('header');
    $header = $('header.main');
    $nav = $header.find('nav');
    $main = $('main');
    $footer = $('footer');
    $alert = $('#alert');
    $popup = $('#popup');
    $seeker = $('.super.seeker');
    $carousel = $('#carousel');
    siteUrl = $body.attr('data-site-url');
    themeUrl = siteUrl + '/wp-content/themes/therevealer';
    assetsUrl = themeUrl + '/assets/';
    transitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd';
    createCarousel = function() {
      var $article, $imgs, $slides;
      $article = $('article.readable');
      $slides = $carousel.find('.slides');
      if ($article.length) {
        $imgs = $article.find('img');
        $imgs.each(function(i, img) {
          var $caption, $image, $newImg, $slide, $wrap, captionHtml, fullImg, fullImgSrc, imgExt, imgSrc, imgSrcEnd;
          $image = $(img).parents('.image');
          $caption = $image.find('.wp-caption-text');
          $newImg = $(img).clone();
          $(img).attr('data-index', i);
          $slide = $('<div class="slide"></div>');
          $wrap = $('<div class="wrap"></div>');
          $slide.attr('data-index', i);
          $wrap.append($newImg);
          $slide.append($wrap);
          if ($caption.length && (captionHtml = $caption.html())) {
            $slide.append('<div class="caption">' + captionHtml + '</div>');
          }
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
          return $carousel.addClass('slidable');
        }
      }
    };
    openCarousel = function(e) {
      var $img, $this, $thisSlide, fullSrc, href, index, isImage, src;
      $carousel.imagesLoaded(function() {
        return $carousel.addClass('loaded');
      });
      if ($carousel.is('.opening')) {
        e.preventDefault();
        return;
      }
      $body.addClass('no_scroll');
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
      $body.removeClass('no_scroll');
      return $carousel.removeClass('show');
    };
    resizeCarousel = function() {
      var $currentSlide, $slides, $slidesWrapper, currentIndex, slidesLength, windowWidth;
      windowWidth = $(window).innerWidth();
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
    };
    fixCarouselHeight = function($slide) {
      var $caption, captionHeight;
      $caption = $slide.find('.caption');
      captionHeight = $caption.innerHeight();
      return console.log(captionHeight);
    };
    clickCarouselArrow = function() {
      var $arrow, direction;
      $arrow = $(this);
      direction = $arrow.attr('data-direction');
      return $carousel.slide(direction);
    };
    $.fn.slide = function(direction, go) {
      var $arrow, $currentSlide, $firstSlide, $lastSlide, $nextSlide, $slidesWrapper, currentIndex, windowWidth;
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
    sizeImages = function(images) {
      var $images;
      if (images) {
        $images = $(images);
      } else {
        $images = $('.image.load');
      }
      $images.each(function(i, image) {
        var $image, $img, $loop, $parent, imageHeight, imageWidth, natHeight, natWidth, ratio, src;
        $image = $(image);
        $img = $image.find('img');
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
          loadImage($image);
        } else if ($image.parents('.cell.discover').length) {
          loadImage($image);
        } else {
          if ($img.length) {
            src = $img.attr('src');
          } else {
            src = $image.attr('data-src');
          }
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
            $image.width(imageWidth);
            $image.height(imageHeight);
            $image.css({
              width: imageWidth,
              height: imageHeight
            });
            return loadImage($image);
          };
          image.onerror = function(e) {
            var img;
            img = e.target;
            return loadImage($image);
          };
          image.src = src;
        }
        return $loop = $image.parents('.loop')[0];
      });
      return $('article.readable .wp-caption').each(function(i, elem) {
        var $caption, $img, imgWidth;
        $img = $(elem).find('img');
        $caption = $(elem).find('.wp-caption-text');
        imgWidth = $img.innerWidth();
        if (!imgWidth) {
          imgWidth = 'auto';
        }
        return $caption.css({
          width: imgWidth
        });
      });
    };
    loadImage = function(image) {
      var $cell, $image, $img, src;
      $image = $(image);
      $cell = $image.parents('.cell');
      if (!$image.is('.load')) {
        return;
      }
      $image.removeClass('load').addClass('loading');
      $img = $image.find('img');
      src = $img.attr('data-src');
      if (!$img.length) {
        src = $image.attr('data-src');
      }
      $image.imagesLoaded({
        background: true
      }).done(function(instance) {
        var $loop;
        $image.removeClass('loading').addClass('loaded');
        if ($img.length) {
          if ($img.parents('aside').length) {
            fixSide();
          } else {
            $image.attr('style', '');
            $image.width('');
            $image.height('');
            if ($loop = $img.parents('.masonry:eq(0)')) {
              fixLoops($loop);
            }
          }
        }
        setTimeout(function() {
          var $masonry;
          if ($masonry = $image.parents('.masonry')) {
            return $masonry.masonry();
          }
        });
        if ($cell.length) {
          return $cell.addClass('show');
        }
      }).fail(function(instance) {
        var $loop;
        $(instance.elements).each(function() {
          var $missingCell, $missingImage, $missingImg, missingUrl;
          $cell = $(this).parents('.cell');
          if ($cell.length) {
            $missingCell = $cell;
          } else {
            $missingCell = $(this);
          }
          if ($missingImg = $missingCell.find('img')) {
            $missingImg.remove();
          }
          $missingImage = $missingCell.find('.image');
          missingUrl = $('#missingSvg').attr('data-url');
          $missingCell.load(missingUrl, null, function() {
            return $missingCell.addClass('missing');
          });
          if ($cell.length) {
            return $cell.addClass('show');
          }
        });
        if ($loop = $img.parents('.masonry:eq(0)')) {
          return fixLoops($loop);
        }
      });
      if ($img.length) {
        return $img.attr('src', src);
      } else {
        return $image.css({
          backgroundImage: 'url(' + src + ')'
        });
      }
    };
    fixLoops = function($loops, $cells) {
      if (!$loops) {
        $loops = $('.loop, .masonry');
      }
      return $loops.each(function() {
        var $first, $loop, columnWidth, gutter, isMasonry;
        $loop = $(this);
        isMasonry = $loop.is('.masonry');
        $first = $loop.find('.cell:eq(0)');
        if (!$first.length) {
          return;
        }
        columnWidth = parseInt($first.css('width'));
        gutter = parseInt($first.css('marginBottom'));
        if (isMasonry) {
          $loop.masonry({
            itemSelector: '.cell',
            columnWidth: $first[0],
            gutter: gutter,
            transitionDuration: 0,
            percentPosition: true,
            fitWidth: true
          });
        }
        if ($cells && $cells.length) {
          if (isMasonry) {
            return $loop.masonry('appended', $cells);
          } else {
            return $loop.append($cells);
          }
        }
      });
    };
    sideScroll = null;
    lastSideScroll = null;
    fixSide = function(e) {
      var headerHeight, isBottom, mainRemain, pageBottom, pageEnd, pageHeight, pageTop, sideHeight, winHeight, winScroll;
      if (!$side.length) {
        return;
      }
      winHeight = $window.innerHeight();
      winScroll = $window.scrollTop();
      pageHeight = $main.outerHeight();
      pageTop = $main.position().top;
      pageBottom = pageTop + pageHeight;
      pageEnd = pageBottom - winHeight;
      isBottom = winScroll >= pageEnd;
      mainRemain = pageEnd - winScroll;
      headerHeight = Math.ceil($header.outerHeight());
      if (mainRemain > 0) {
        sideHeight = $window.innerHeight() - headerHeight;
        $side.css({
          y: 0
        });
      } else {
        sideHeight = '100%';
        $side.css({
          y: mainRemain
        });
      }
      return $side.css({
        height: sideHeight,
        top: headerHeight
      });
    };
    trackScroll = function(e) {
      var $readable, alertHeight, belowThresh, headerBottom, innerHeight, isBottom, pageBottom, pageEnd, pageHeight, pageTop, popupObj, scrollHeight, top, winHeight, winScroll;
      $readable = $('.readable');
      if (!$readable.length || $body.is('.no_scroll')) {
        return;
      }
      winHeight = $window.innerHeight();
      winScroll = $window.scrollTop();
      scrollHeight = $body[0].scrollHeight;
      pageHeight = $main.innerHeight();
      pageTop = $main.position().top;
      pageBottom = pageTop + pageHeight;
      pageEnd = pageBottom - winHeight;
      isBottom = winScroll >= pageEnd;
      if (alertHeight = $alert.innerHeight()) {
        pageTop = alertHeight;
      } else {
        pageTop = 0;
      }
      if ($body.is('.discover')) {
        if (winScroll + winHeight >= scrollHeight - winHeight * 2) {
          setTimeout(function() {
            return queryMore();
          }, 3000);
        }
      } else {
        $nav = $header.find('nav');
        headerBottom = $header.offset().top + $header.innerHeight();
        innerHeight = $nav.outerHeight();
        if (isBottom) {
          $wrapper.addClass('bottom');
          $header.css({
            y: pageEnd - winScroll
          });
        } else {
          $header.css({
            y: 0
          });
          if ($alert.length) {
            top = alertHeight;
            winScroll += alertHeight;
          } else {
            top = 0;
          }
          $wrapper.removeClass('bottom');
        }
      }
      belowThresh = pageEnd / 4 - winScroll <= 0;
      if ($popup.length && !$popup.is('.stuck')) {
        popupObj = JSON.stringify({
          shown: true,
          time: new Date().getTime()
        });
        if (winScroll - $popup.innerHeight() - $header.innerHeight() > pageEnd) {
          $popup.addClass('show stuck').removeClass('fixed');
          localStorage.setItem('popup', popupObj);
          $popup.transition({
            y: 0
          }, 0);
        } else if (belowThresh && !$popup.is('.stuck, .fixed')) {
          $popup.addClass('show fixed').removeClass('stuck');
          localStorage.setItem('popup', popupObj);
          $popup.transition({
            y: -$popup.innerHeight()
          }, 250);
        }
      }
      return fixSide(e);
    };
    toggleToggler = function() {
      var $intra, $toggle, $toggler, data, height;
      $toggle = $(this);
      data = $toggle.attr('data-toggle');
      $toggler = $('.toggler').filter('[data-toggle="' + data + '"]');
      $intra = $toggler.find('.intra');
      $toggler.toggleClass('toggled');
      if ($toggler.is('.toggled')) {
        height = $intra[0].scrollHeight;
        $toggler.css({
          maxHeight: height
        });
        if ($toggle.is('.filters')) {
          $('html,body').animate({
            scrollTop: 0
          });
        }
      } else {
        $toggler.attr('style', '');
      }
      return $toggle.toggleClass('toggled');
    };
    fixToggler = function() {
      return $('.toggler:not(.navigation)').each(function(i, toggler) {
        var $inner, $toggler;
        $toggler = $(toggler);
        $inner = $toggler.find('.intra');
        if ($inner.innerHeight() <= $toggler.innerHeight() + 5) {
          return $toggler.addClass('no-toggle');
        } else {
          return $toggler.removeClass('no-toggle');
        }
      });
    };
    setupArticle = function() {
      var $article, $content, $inlineImg, $link, $wpImg, currentSrc, hasImages, href, inlineImg, inlineImgs, j, k, len, len1, link, links, name, pseudo, replace, split;
      if (!$body.is('.single-post, .page-template-default')) {
        return;
      }
      $article = $('article.readable');
      $content = $article.find('.text .content');
      inlineImgs = $content.find('img');
      hasImages = false;
      for (j = 0, len = inlineImgs.length; j < len; j++) {
        inlineImg = inlineImgs[j];
        $inlineImg = $(inlineImg);
        $wpImg = $inlineImg.parents('a, .aligncenter, .alignleft, .alignright, .wp-caption');
        $wpImg.filter('a').removeClass('href');
        if ($wpImg.length) {
          $wpImg.addClass('image load');
        } else {
          $inlineImg.wrap('<div class="shift image load"></div>');
        }
        currentSrc = inlineImg.currentSrc;
        $inlineImg.attr('data-src', currentSrc);
        pseudo = new Image();
        pseudo.onload = function(e) {
          var $cell, $cellImage, $thumb, imageHeight, imageWidth, img;
          img = e.target;
          $cell = $('<div class="cell transport"><div class="image load"></div></div>');
          imageWidth = img.naturalWidth;
          imageHeight = img.naturalHeight;
          $cell.find('.image').append(img);
          $thumb = $cell.find('img');
          $thumb.attr('data-width', imageWidth).attr('data-height', imageHeight);
          $cellImage = $cell.find('.image');
          return sizeImages($cellImage);
        };
        pseudo.onerror = function(e) {};
        pseudo.src = currentSrc;
      }
      sizeImages($article.find('.content .image.load'));
      if ($carousel.length) {
        createCarousel();
      }
      links = $article.find('a[href]');
      for (k = 0, len1 = links.length; k < len1; k++) {
        link = links[k];
        $link = $(link);
        href = $link.attr('href');
        if (href.includes('#_ftn') || href.includes('#_edn')) {
          replace = $link.text().replace('[', '').replace(']', '').replace('(', '').replace(')', '');
          $link.text(replace);
          if (href.includes('#_ftnref') || href.includes('#_ednref')) {
            name = href.replace('ref', '').replace('#', '');
            $link.attr('name', name).addClass('ref ftn transport');
          } else {
            name = href.replace('#', '');
            split = name.split(/(\d+)/);
            name = split[0] + 'ref' + split[1];
            $link.attr('name', name).addClass('super ftn transport');
          }
        } else if (!href.includes(siteUrl)) {
          $link.attr('target', '_blank');
        }
      }
      return $article.addClass('show');
    };
    transport = function(e) {
      var $button, $image, $img, $inline, $readable, hasOffset, headerHeight, href, scrollTo, scrollTop, src;
      $button = $(this);
      $header = $header;
      scrollTop = $('html,body').scrollTop();
      headerHeight = $header.innerHeight();
      if ($button.is('.top')) {
        scrollTo = 0;
      } else if ($button.is('.ftn')) {
        e.preventDefault();
        href = $button.attr('href').replace('#', '');
        $inline = $('a').filter('[name="' + href + '"]');
      } else {
        $image = $button.find('.image');
        $img = $image.find('img');
        src = $img.attr('src');
        if ($readable = $('.readable')) {
          $inline = $readable.find('img').filter('[data-src="' + src + '"]');
        }
      }
      hasOffset = $inline && $inline.length;
      if (hasOffset) {
        scrollTo = $inline.offset().top - headerHeight - 20;
      }
      if (!isNaN(scrollTo)) {
        return $('html,body').animate({
          scrollTop: scrollTo
        }, function() {
          if (hasOffset) {
            return $inline.focus();
          }
        });
      }
    };
    hoverCell = function() {
      var $cell;
      $cell = $(this).parents('.cell:eq(0)');
      return $cell.toggleClass('hover');
    };
    fixHeader = function() {
      var $filtersToggle, linksHeight, linksWidth, taglineHeight, taglineWidth, topHeight, windowHeight;
      windowHeight = $window.innerHeight();
      topHeight = Math.ceil($header.outerHeight());
      $wrapper.css({
        marginTop: topHeight
      });
      if ($filtersToggle = $('.toggle.filters')) {
        $filtersToggle.css({
          y: topHeight
        });
      }
      if ($seeker) {
        $seeker.css({
          height: windowHeight - topHeight,
          marginTop: topHeight
        });
      }
      $body.addClass('initd');
      $side.addClass('fixed');
      taglineHeight = $nav.find('.tagline').innerHeight();
      linksHeight = $nav.find('.links').innerHeight();
      taglineWidth = $nav.find('.tagline').innerWidth();
      return linksWidth = $nav.find('.links').innerWidth();
    };
    toggleSeeker = function(e) {
      var $link, title;
      $link = $(this);
      title = $link.data('title');
      if (title !== 'Search') {
        return;
      }
      e.preventDefault();
      if (!$body.hasClass('search')) {
        $body.toggleClass('no_scroll');
        $('.toggled[data-toggle="nav"]').removeClass('toggled').attr('style', '');
        $seeker.toggleClass('open');
        if ($seeker.is('.open')) {
          return $seeker.find('input[type="search"]').focus();
        } else {
          return $seeker.find('input[type="search"]').blur();
        }
      } else {
        $seeker = $('.seeker:first-child');
        return $seeker.find('input[type="search"]').focus();
      }
    };
    closeSeeker = function(e) {
      $seeker = $('.super.seeker');
      return $seeker.removeClass('open');
    };
    closeAlert = function() {
      $alert.remove();
      fixHeader();
      return trackScroll();
    };
    closePopup = function() {
      return $popup.transition({
        y: $popup.innerHeight()
      }, 500, function() {
        $popup.removeClass('fixed');
        return $popup.addClass('stuck');
      });
    };
    isMobile = function() {
      return parseInt($('#is_mobile').css('content').replace(/['"]+/g, ''));
    };
    window.discovered = [];
    queryMore = function() {
      var $cell, $loop, i;
      $loop = $('.discover.loop');
      if (!$main.is('.querying')) {
        if (!discovered.length) {
          $loop.find('.cell').each(function(i, cell) {
            var $cell, id;
            $cell = $(cell);
            id = $cell.attr('data-id');
            return discovered.push(id);
          });
        }
        i = 0;
        while (i < 50) {
          $cell = $('<div class="cell discover thumb empty"><div class="wrap"><div class="circle"></div></div></div>');
          $loop.append($cell);
          i++;
        }
        $main.addClass('querying');
        return $.ajax({
          url: wp_api.ajax_url,
          type: 'POST',
          data: {
            action: 'api_query',
            discovered: discovered
          },
          success: function(cells, status, jqXHR) {
            var $empties;
            $main.removeClass('querying');
            $empties = $loop.find('.empty.cell');
            return $empties.each(function(i, empty) {
              var $empty, $image, $inner, id;
              $empty = $(empty);
              $cell = $(cells).eq(i);
              id = $cell.attr('data-id');
              discovered.push(id);
              if ($cell.length) {
                $inner = $cell.find('.wrap').html();
                $empty.removeClass('empty');
                $empty.find('.wrap').html($inner);
              }
              $image = $empty.find('.image.load');
              if ($image.length) {
                return sizeImages($image);
              } else {
                return $empty.addClass('show');
              }
            });
          },
          error: function(jqXHR, status, error) {
            return console.log(jqXHR.responseJSON);
          }
        });
      }
    };
    shareWindow = function(e) {
      var href;
      e.preventDefault();
      href = this.href;
      return window.open(href, 'popup', 'width=600,height=600,scrollbars=no,resizable=no');
    };
    animateTexts = function() {
      return setTimeout(function() {
        return $('.glisten').each(function(ri, wrap) {
          var $spans, $words, $wrap;
          ri++;
          $wrap = $(wrap);
          $words = $wrap.find('.word');
          $wrap.empty();
          $words.each(function(i, word) {
            var $span, $wordSpan, char, chars, j, len;
            $wordSpan = $(word);
            word = $wordSpan.text();
            chars = word.split('');
            $wordSpan.empty();
            for (j = 0, len = chars.length; j < len; j++) {
              char = chars[j];
              $span = $('<span class="char">' + char + '</span>');
              $wordSpan.append($span);
            }
            return $wrap.append($wordSpan);
          });
          $spans = $wrap.find('span.char');
          $spans.each(function(si, span) {
            return animateText(span, si);
          });
          return $wrap.addClass('show');
        });
      }, 400);
    };
    animateText = function(html, index) {
      return setTimeout(function() {
        return $(html).addClass('animate');
      }, 50 * index);
    };
    $('body').on('click touchend', 'article.readable a, article.readable img', openCarousel);
    $('body').on('click touchend', '#carousel .close', closeCarousel);
    $('body').on('click touchend', '#carousel.loaded.slidable .arrow:not(.no)', clickCarouselArrow);
    $('body').on('click touchend', '.transport', transport);
    $('body').on('click touchend', '.toggle[data-toggle]', toggleToggler);
    $('body').on('click touchend', '#alert .close', closeAlert);
    $('body').on('click touchend', '#popup .close', closePopup);
    $('body').on('hover', '.cell .link_wrap', hoverCell);
    $('body').on('click touchend', 'header nav .link a', toggleSeeker);
    $('body').on('click touchend', '.super.seeker .close', closeSeeker);
    if ($popup.length) {
      popupObj = JSON.parse(localStorage.getItem('popup'));
      now = new Date().getTime();
      dur = 60 * 15 * 1000;
      lastWeek = now - dur;
      if (popupObj && popupObj.shown && popupObj.time > lastWeek) {
        $popup.addClass('show stuck');
      }
    }
    if ($body.is('.search')) {
      $('.search_header input#searchbox').focus();
    }
    $window.on('resize', function() {
      fixLoops();
      sizeImages();
      fixHeader();
      trackScroll();
      return fixToggler();
    }).resize();
    $window.on('scroll', function(e) {
      trackScroll(e);
      return fixHeader();
    });
    setupArticle();
    animateTexts();
    watchForHover = function() {
      var disableHover, enableHover, hasHoverClass, lastTouchTime, updateLastTouchTime;
      hasHoverClass = false;
      lastTouchTime = 0;
      enableHover = function() {
        if (new Date() - lastTouchTime < 500) {
          return;
        }
        $body.addClass('has_hover');
        return hasHoverClass = true;
      };
      disableHover = function() {
        $body.removeClass('has_hover');
        return hasHoverClass = false;
      };
      updateLastTouchTime = function() {
        return lastTouchTime = new Date();
      };
      document.addEventListener('touchstart', updateLastTouchTime, true);
      document.addEventListener('touchstart', disableHover, true);
      document.addEventListener('mousemove', enableHover, true);
      return enableHover();
    };
    return watchForHover();
  });
});
