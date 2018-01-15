jQuery(function($) {
  return $(function() {
    var $alert, $body, $footer, $header, $headers, $logo, $main, $nav, $popup, $side, $window, $wrapper, animateText, animateTexts, assetsUrl, closeAlert, closePopup, closeSeeker, dur, fixHeader, fixLoops, fixSide, fixToggler, hoverCell, isMobile, lastSideScroll, lastWeek, loadImage, now, popupObj, queryMore, setupArticle, shareWindow, sideScroll, siteUrl, sizeImages, themeUrl, toggleSeeker, toggleToggler, trackScroll, transport;
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
    siteUrl = $body.attr('data-site-url');
    themeUrl = siteUrl + '/wp-content/themes/therevealer';
    assetsUrl = themeUrl + '/assets/';
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
          src = src.replace('http://', 'https://');
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
      if (!$readable.length) {
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
        return $toggler.css({
          maxHeight: height
        });
      } else {
        return $toggler.attr('style', '');
      }
    };
    fixToggler = function() {
      return $('.toggler:not(.navigation)').each(function(i, toggler) {
        var $inner, $toggler;
        $toggler = $(toggler);
        $inner = $toggler.find('.inner');
        if ($inner.innerHeight() <= $toggler.innerHeight() + 5) {
          return $toggler.addClass('toggled');
        } else {
          return $toggler.removeClass('toggled');
        }
      });
    };
    setupArticle = function() {
      var $article, $content, $elems, $inlineImg, $link, $wpImg, currentSrc, goodTags, hasImages, href, inlineImg, inlineImgs, j, k, len, len1, link, links, name, pseudo, replace, split;
      if (!$body.is('.single-post, .page-template-default')) {
        return;
      }
      $article = $('article.readable');
      $content = $article.find('.text .content');
      goodTags = 'p,a,em,img,blockquote,object,.wp-caption-text,.wp-caption,.image';
      $elems = $content.find('*:not(' + goodTags + ')').contents();
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
      var linksHeight, linksWidth, taglineHeight, taglineWidth, topHeight;
      topHeight = Math.ceil($header.outerHeight());
      $wrapper.css({
        marginTop: topHeight
      });
      $body.addClass('initd');
      $side.addClass('fixed');
      taglineHeight = $nav.find('.tagline').innerHeight();
      linksHeight = $nav.find('.links').innerHeight();
      taglineWidth = $nav.find('.tagline').innerWidth();
      return linksWidth = $nav.find('.links').innerWidth();
    };
    toggleSeeker = function(e) {
      var $link, $seeker, title;
      $link = $(this);
      title = $link.data('title');
      if (title !== 'Search') {
        return;
      }
      e.preventDefault();
      if (!$body.hasClass('search')) {
        $seeker = $('.seeker.beyond');
        $seeker.toggleClass('open');
        if ($seeker.is('.open')) {
          return $seeker.find('input').focus();
        } else {
          return $seeker.find('input').blur();
        }
      } else {
        $seeker = $('.seeker:first-child');
        return $seeker.find('input').focus();
      }
    };
    closeSeeker = function(e) {
      var $seeker;
      console.log('!!');
      $seeker = $('.seeker.beyond');
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
      return parseInt($('#isMobile').css('content').replace(/['"]+/g, ''));
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
    $('body').on('click touch', '.transport', transport);
    $('body').on('click', '.toggle[data-toggle]', toggleToggler);
    $('body').on('click touch', '#alert .close', closeAlert);
    $('body').on('click touch', '#popup .close', closePopup);
    $('body').on('hover', '.cell .link_wrap', hoverCell);
    $('body').on('click', 'aside .share a.window', shareWindow);
    $('body').on('click', 'header nav .link a', toggleSeeker);
    $('body').on('click', '.seeker.beyond .close', closeSeeker);
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
    return animateTexts();
  });
});
