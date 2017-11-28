jQuery(function($) {
  return $(function() {
    var $alert, $body, $footer, $header, $headers, $logo, $main, $nav, $popup, $side, $window, $wrapper, assetsUrl, closeAlert, closePopup, fixHeader, fixLoops, fixSide, hoverCell, isMobile, lastSideScroll, lastWeek, loadImage, now, popupObj, queryMore, setupArticle, shareWindow, sideScroll, siteUrl, sizeImages, themeUrl, toggleHeight, trackScroll, transport, week;
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
        var $image, $img, $masonry, $parent, imageHeight, imageWidth, natHeight, natWidth, ratio, src;
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
        if ($masonry = $image.parents('.masonry')) {
          return $masonry.masonry();
        }
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
          var $missing, $missingSvg;
          $cell = $(this).parents('.cell');
          if ($cell.length) {
            $missing = $cell;
          } else {
            $missing = $(this);
          }
          $missing.addClass('missing');
          $missingSvg = $('#missingSvg svg');
          $missing.html($missingSvg);
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
          queryMore();
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
    toggleHeight = function() {
      var $list, $listWrap, $toggle, height;
      $toggle = $(this);
      $listWrap = $toggle.parents('.listWrap');
      $list = $listWrap.find('.list');
      $listWrap.toggleClass('show');
      if ($listWrap.is('.show')) {
        height = $list[0].scrollHeight;
        return $listWrap.css({
          maxHeight: height
        });
      } else {
        return $listWrap.attr('style', '');
      }
    };
    setupArticle = function() {
      var $article, $inlineImg, $link, $sideImages, $wpImg, currentSrc, hasImages, href, inlineImg, inlineImgs, j, k, len, len1, link, links, pseudo, replace;
      if (!$body.is('.single-post')) {
        return;
      }
      $article = $('article');
      $sideImages = $side.find('.images .loop');
      $sideImages.masonry();
      fixLoops($sideImages);
      inlineImgs = $article.find('.content img');
      hasImages = false;
      for (j = 0, len = inlineImgs.length; j < len; j++) {
        inlineImg = inlineImgs[j];
        $inlineImg = $(inlineImg);
        $wpImg = $inlineImg.parents('.aligncenter, .alignleft, .alignright, .wp-caption');
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
          fixLoops($sideImages, $cell);
          $cellImage = $cell.find('.image');
          sizeImages($cellImage);
          return $sideImages.parents('.images').removeClass('hide');
        };
        pseudo.onerror = function(e) {
          return console.log(this, e);
        };
        pseudo.src = currentSrc;
      }
      sizeImages($article.find('.content .image.load'));
      links = $article.find('a[href]');
      for (k = 0, len1 = links.length; k < len1; k++) {
        link = links[k];
        $link = $(link);
        href = $link.attr('href');
        if (href.includes('#_ftn')) {
          replace = $link.text().replace('[', '').replace(']', '');
          $link.text(replace);
          if (href.includes('#_ftnref')) {
            $link.addClass('ref ftn transport');
          } else {
            $link.addClass('super ftn transport');
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
        while (i < 15) {
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
    setTimeout(function() {
      return $('.glisten').each(function(ri, wrap) {
        var $spans, $wrap, characters;
        ri++;
        $wrap = $(wrap);
        characters = $wrap.text().split('');
        $wrap.empty();
        $(characters).each(function(ci, html) {
          var $span;
          $span = $('<span>' + html + '</span>');
          return $wrap.append($span);
        });
        $spans = $wrap.find('span');
        return setTimeout(function() {
          $spans.each(function(si, span) {
            var $span;
            si++;
            $span = $(span);
            return setTimeout(function() {
              return $span.addClass('animate');
            }, si * 50);
          });
          return $wrap.addClass('show');
        }, 100 * ri);
      }, 100);
    });
    $('#logo svg path').each(function(i, path) {
      return setTimeout(function() {
        return $(path).addClass('animate');
      }, i * 50);
    });
    $('body').on('click', '.transport', transport);
    $('body').on('click', '.toggle', toggleHeight);
    $('body').on('click', '#alert .close', closeAlert);
    $('body').on('click', '#popup .close', closePopup);
    $('body').on('hover', '.cell .link_wrap', hoverCell);
    $('body').on('click', 'aside .share a.window', shareWindow);
    if ($popup.length) {
      popupObj = JSON.parse(localStorage.getItem('popup'));
      now = new Date().getTime();
      week = 60 * 60 * 24 * 7 * 1000;
      lastWeek = now - week;
      if (popupObj.shown && popupObj.time > lastWeek) {
        $popup.addClass('show stuck');
      }
    }
    if ($body.is('.search')) {
      $('input#searchbox').focus();
    }
    $window.on('resize', function() {
      fixLoops();
      sizeImages();
      fixHeader();
      return trackScroll();
    }).resize();
    $window.on('scroll', function(e) {
      trackScroll(e);
      return fixHeader();
    });
    return setupArticle();
  });
});
