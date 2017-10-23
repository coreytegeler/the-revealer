jQuery(function($) {
  return $(function() {
    var $alert, $body, $footer, $header, $headers, $logo, $main, $nav, $popup, $side, $window, $wrapper, closeAlert, closePopup, fixGrids, fixHeader, fixSide, hoverCell, isMobile, lastSideScroll, loadImage, queryMore, reveal, setupArticle, sideScroll, siteUrl, sizeImages, toggleFilterList, trackScroll, transport;
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
    sizeImages = function(images) {
      var $images;
      if (images) {
        $images = $(images);
      } else {
        $images = $('.image.load');
      }
      return $images.each(function(i, image) {
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
    };
    loadImage = function(image) {
      var $image, $img, src;
      $image = $(image);
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
        $image.removeClass('loading').addClass('loaded');
        if ($img.length) {
          if ($img.parents('aside').length) {
            fixSide();
          } else {
            $image.attr('style', '');
            $image.width('');
            $image.height('');
            fixGrids();
          }
        }
        return setTimeout(function() {
          var $masonry;
          if ($masonry = $image.parents('.masonry')) {
            return $masonry.masonry();
          }
        });
      }).fail(function(instance) {
        $(instance.elements).each(function() {
          var $cell;
          $cell = $(this).parents('.cell');
          if ($cell.length) {
            return $cell.remove();
          } else {
            return $(this).remove();
          }
        });
        return fixGrids();
      });
      if ($img.length) {
        return $img.attr('src', src);
      } else {
        return $image.css({
          backgroundImage: 'url(' + src + ')'
        });
      }
    };
    fixGrids = function($loops, $cells) {
      if (!$loops) {
        $loops = $('.loop, .masonry');
      }
      return $loops.each(function() {
        var $first, $loop, columnWidth, gutter, isMasonry;
        $loop = $(this);
        isMasonry = $loop.is('.masonry');
        if ($cells) {
          if (isMasonry) {
            $loop.masonry();
          }
          $loop.append($cells);
          if (isMasonry) {
            $loop.masonry('appended', $cells);
          }
        }
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
        return sizeImages($loop.find('.image.load'));
      });
    };
    sideScroll = null;
    lastSideScroll = null;
    fixSide = function(e) {
      var isBottom, mainRemain, nextSideScroll, pageBottom, pageEnd, pageHeight, pageTop, scrollDiff, sideHeight, sideRemain, sideScrollHeight, sideScrolled, sideTop, winHeight, winScroll;
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
      if (mainRemain > 0) {
        $side.css({
          y: 0
        });
      } else {
        $side.css({
          y: mainRemain
        });
      }
      sideTop = $side.position().top;
      sideHeight = $side.innerHeight();
      sideScroll = $side.scrollTop();
      sideScrollHeight = $side[0].scrollHeight;
      sideScrolled = sideHeight + sideScroll;
      sideRemain = sideScrollHeight - sideScrolled;
      scrollDiff = sideRemain - mainRemain;
      return nextSideScroll = scrollDiff;
    };
    trackScroll = function(e) {
      var $readable, alertHeight, belowThresh, isBottom, pageBottom, pageEnd, pageHeight, pageTop, scrollHeight, winHeight, winScroll;
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
      $headers.each(function(i, header) {
        var headerBottom, innerHeight, top;
        $header = $(header);
        $nav = $header.find('nav');
        headerBottom = $header.offset().top + $header.innerHeight();
        innerHeight = $nav.outerHeight();
        if (isBottom) {
          $wrapper.addClass('bottom');
          return $header.css({
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
          return $wrapper.removeClass('bottom');
        }
      });
      if ($readable.is('#discover')) {
        if (winScroll + winHeight >= scrollHeight - winHeight * 2) {
          queryMore();
        }
      }
      belowThresh = pageEnd / 4 - winScroll <= 0;
      if ($popup.length && !$popup.is('.stuck')) {
        if (winScroll - $popup.innerHeight() - $header.innerHeight() > pageEnd) {
          $popup.addClass('show').removeClass('fixed').addClass('stuck');
          $popup.transition({
            y: 0
          }, 0);
        } else if (belowThresh && !$popup.is('.stuck, .fixed')) {
          $popup.addClass('show').addClass('fixed');
          $popup.transition({
            y: -$popup.innerHeight()
          }, 250);
        } else if (!$popup.is('.fixed')) {
          $popup.removeClass('show');
        }
      }
      return fixSide(e);
    };
    toggleFilterList = function() {
      var $filter, $list, $toggle, height;
      $toggle = $(this);
      $filter = $toggle.parents('.filter');
      $list = $filter.find('.list');
      $list.toggleClass('show');
      if ($list.is('.show')) {
        height = $list[0].scrollHeight;
      } else {
        height = 0;
      }
      return $list.css({
        height: height
      });
    };
    setupArticle = function() {
      var $article, $inlineImg, $link, $sideImages, currentSrc, href, inlineImg, inlineImgs, j, k, len, len1, link, links, pseudo, replace;
      if (!$body.is('.single-post')) {
        return;
      }
      $article = $('article');
      $sideImages = $side.find('.images');
      $sideImages.masonry();
      fixGrids($sideImages);
      inlineImgs = $article.find('.content img');
      for (j = 0, len = inlineImgs.length; j < len; j++) {
        inlineImg = inlineImgs[j];
        $inlineImg = $(inlineImg);
        currentSrc = $inlineImg[0].currentSrc;
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
          fixGrids($sideImages, $cell);
          $cellImage = $cell.find('.image');
          return sizeImages($cellImage);
        };
        pseudo.src = currentSrc;
      }
      $sideImages.removeClass('hide');
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
    reveal = function() {
      return $('.reveal').each(function(i, elem) {
        var delay;
        delay = (Math.random() * (50 - 20) + 20) + (i * 20);
        return setTimeout(function() {
          return $(elem).addClass('show');
        }, delay);
      });
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
          $inline = $readable.find('img').filter('[src="' + src + '"]');
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
      $cell = $(this).parents('.cell');
      return $cell.toggleClass('hover');
    };
    fixHeader = function() {
      var linksHeight, linksWidth, taglineHeight, taglineWidth, topHeight;
      topHeight = Math.ceil($header.outerHeight());
      $wrapper.css({
        marginTop: topHeight
      });
      $body.addClass('initd');
      $side.css({
        height: $window.innerHeight() - topHeight,
        top: topHeight
      });
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
      if (!$loop.is('.querying')) {
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
          $cell = $('<div class="cell discover reveal thumb show empty"><div class="wrap"><div class="circle"></div></div></div>');
          $loop.append($cell);
          i++;
        }
        reveal();
        $loop.addClass('querying');
        return $.ajax({
          url: wp_api.ajax_url,
          type: 'POST',
          data: {
            action: 'api_query',
            discovered: discovered
          },
          success: function(cells, status, jqXHR) {
            var $empties;
            $loop.removeClass('querying');
            $empties = $loop.find('.empty.cell');
            return $empties.each(function(i, empty) {
              var $empty, $inner, id;
              $empty = $(empty);
              $cell = $(cells).eq(i);
              id = $cell.attr('data-id');
              discovered.push(id);
              if ($cell.length) {
                $inner = $cell.find('.wrap').html();
                $empty.removeClass('empty');
                $empty.find('.wrap').html($inner);
              }
              return sizeImages($empty.find('.image.load'));
            });
          },
          error: function(jqXHR, status, error) {
            return console.log(jqXHR.responseJSON);
          }
        });
      }
    };
    $.fn.scatter = function() {
      var $cells, $masonry;
      $masonry = $(this);
      $cells = $masonry.find('.cell');
      return $cells.filter(':not(.scattered)').each(function(i, cell) {
        var $cell, $wrap, max, min, padding, x, y;
        $cell = $(this);
        $wrap = $cell.find('.wrap');
        padding = parseInt($wrap.css('padding'));
        max = padding;
        min = -max;
        x = Math.random() * (max - min) + min;
        y = Math.random() * (max - min) + min;
        $wrap.css({
          x: x,
          y: y
        });
        return $cell.addClass('scattered');
      });
    };
    $('.glisten').each(function(ri, html) {
      var $html, characters;
      ri++;
      $html = $(html);
      characters = $html.text().split('');
      $html.empty();
      $(characters).each(function(ci, html) {
        var $span;
        $span = $('<span>' + html + '</span>');
        return $html.append($span);
      });
      return setTimeout(function() {
        return $html.find('span').each(function(si, span) {
          si++;
          return setTimeout(function() {
            return $(span).addClass('animate');
          }, si * 50);
        });
      }, 500 * ri);
    });
    $('#logo svg path').each(function(i, path) {
      return setTimeout(function() {
        return $(path).addClass('animate');
      }, i * 50);
    });
    $('body').on('click', '.transport', transport);
    $('body').on('click', '#filters .toggle', toggleFilterList);
    $('body').on('click', '#alert .close', closeAlert);
    $('body').on('click', '#popup .close', closePopup);
    $('body').on('hover', '.cell .link_wrap', hoverCell);
    $window.on('resize', function() {
      fixGrids();
      sizeImages();
      fixHeader();
      return trackScroll();
    }).resize();
    $window.on('scroll', function(e) {
      return trackScroll(e);
    });
    setupArticle();
    return reveal();
  });
});
