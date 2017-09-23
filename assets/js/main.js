jQuery(function($) {
  return $(function() {
    var $body, $footer, $header, $headers, $logo, $main, $nav, $side, $topBar, $window, $wrapper, closeTopBar, fixGrids, fixHeader, fixSide, isMobile, loadImage, queryMore, reveal, setupArticle, siteUrl, sizeImages, toggleFilterList, trackScroll, transport;
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
    $topBar = $('#top_bar');
    siteUrl = $body.attr('data-site-url');
    sizeImages = function() {
      return $('.image.load').each(function(i, image) {
        var $image, $img, $parent, imageHeight, imageWidth, natHeight, natWidth, ratio, src;
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
          return loadImage($image);
        } else if ($image.parents('.cell.discover').length) {
          return loadImage($image);
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
          return image.src = src;
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
            fixGrids();
          }
        }
        return setTimeout(function() {
          var $grid;
          if ($grid = $image.parents('.grid')) {
            return $grid.masonry();
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
        if ($grid.is('.discover')) {
          $grid.scatter();
        }
        $first = $grid.find('.cell:eq(0)');
        if (!$first.length) {
          return;
        }
        columnWidth = parseInt($first.css('width'));
        gutter = parseInt($first.css('marginBottom'));
        return $grid.masonry({
          itemSelector: '.cell',
          columnWidth: $first[0],
          gutter: gutter,
          transitionDuration: 0,
          percentPosition: true,
          fitWidth: true
        });
      });
    };
    fixSide = function(e) {
      var isBottom, pageBottom, pageEnd, pageHeight, pageTop, sideHeight, sideScroll, sideScrollHeight, winHeight, winScroll;
      return;
      if (!$side.length) {
        return;
      }
      winHeight = $window.innerHeight();
      winScroll = $window.scrollTop();
      pageHeight = $main.innerHeight();
      pageTop = $main.position().top;
      pageBottom = pageTop + pageHeight;
      pageEnd = pageBottom - winHeight;
      isBottom = winScroll >= pageEnd;
      sideHeight = $side.innerHeight();
      sideScrollHeight = $side[0].scrollHeight;
      return sideScroll = $side.scrollTop();
    };
    trackScroll = function(e) {
      var $readable, isBottom, nearBottom, notSideBottom, pageBottom, pageEnd, pageHeight, pageTop, scrollHeight, scrollPast, sideBottom, sideEnd, sideHeight, sideScroll, sideTop, topBarHeight, winHeight, winScroll;
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
      if (topBarHeight = $topBar.innerHeight()) {
        pageTop = topBarHeight;
      } else {
        pageTop = 0;
      }
      $headers.each(function(i, header) {
        var headerBottom, innerHeight, sideScroll, top;
        $header = $(header);
        $nav = $header.find('nav');
        headerBottom = $header.offset().top + $header.innerHeight();
        innerHeight = $nav.outerHeight();
        if (isBottom) {
          sideScroll = $side.scrollTop();
          $wrapper.addClass('bottom');
          $header.css({
            y: pageEnd - winScroll
          });
        } else {
          $header.css({
            y: 0
          });
          if ($topBar.length) {
            top = topBarHeight;
            winScroll += topBarHeight;
          } else {
            top = 0;
          }
          $wrapper.removeClass('bottom');
        }
        return $header.find('.bar').each(function(i, bar) {
          var $bar, barWidth, progress;
          $bar = $(this);
          if ($bar.is('.prog')) {
            barWidth = $(bar).innerWidth();
            progress = winScroll * barWidth / pageEnd;
            return $bar.find('.solid').css({
              width: progress
            });
          }
        });
      });
      if ($side.length) {
        sideTop = $side.position().top;
        sideHeight = $side.innerHeight();
        sideBottom = sideTop + sideHeight;
        sideScroll = $side.scrollTop();
        sideEnd = sideBottom - $side.innerHeight();
        scrollPast = winScroll - pageEnd;
        notSideBottom = sideScroll < $side[0].scrollHeight - sideHeight;
        if (notSideBottom) {
          $side.scrollTop(scrollPast);
        }
      }
      nearBottom = winScroll + winHeight >= scrollHeight - winHeight * 2;
      if ($readable.is('#discover') && nearBottom) {
        queryMore();
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
      var $article, $link, href, inlineImg, inlineImgs, j, k, len, len1, link, links, replace;
      if (!$body.is('.single-post')) {
        return;
      }
      $article = $('article');
      inlineImgs = $article.find('.content img');
      for (j = 0, len = inlineImgs.length; j < len; j++) {
        inlineImg = inlineImgs[j];
        $(inlineImg).wrap('<div class="image load"></div>');
      }
      sizeImages();
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
      var $button, $image, $img, $inline, $readable, hasOffset, headerBottom, href, scrollTo, scrollTop, src;
      $button = $(this);
      $header = $header;
      scrollTop = $('html,body').scrollTop();
      headerBottom = $header.innerHeight();
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
        scrollTo = $inline.offset().top - scrollTop - headerBottom * 2;
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
    fixHeader = function() {
      var linksHeight, linksWidth, taglineHeight, taglineWidth, topHeight;
      topHeight = Math.ceil($header.outerHeight());
      $wrapper.css({
        paddingTop: topHeight
      });
      $side.css({
        height: $window.innerHeight() - topHeight,
        top: topHeight
      });
      taglineHeight = $nav.find('.tagline').innerHeight();
      linksHeight = $nav.find('.links').innerHeight();
      taglineWidth = $nav.find('.tagline').innerWidth();
      return linksWidth = $nav.find('.links').innerWidth();
    };
    closeTopBar = function() {
      $topBar.remove();
      fixHeader();
      return trackScroll();
    };
    isMobile = function() {
      return parseInt($('#isMobile').css('content').replace(/['"]+/g, ''));
    };
    queryMore = function() {
      var $cell, $grid, i;
      $grid = $('.discover.grid');
      if (!$grid.is('.querying')) {
        i = 0;
        while (i < 15) {
          $cell = $('<div class="cell discover reveal empty"><div class="wrap"><div class="circle"></div></div></div>');
          $grid.append($cell);
          $grid.masonry('appended', $cell);
          $grid.masonry();
          i++;
        }
        $grid.scatter();
        reveal();
        $grid.addClass('querying');
        return $.ajax({
          url: wp_api.ajax_url,
          type: 'POST',
          data: {
            action: 'api_query',
            orderby: 'rand',
            post_type: 'post',
            posts_per_page: 15,
            meta_query: {
              key: '_thumbnail_id',
              compare: 'EXISTS'
            }
          },
          success: function(data, status, jqXHR) {
            var $empties;
            $grid.removeClass('querying');
            $empties = $grid.find('.empty.cell');
            return $(data).each(function(i, cell) {
              var $empty, $inner;
              $inner = $(cell).find('.wrap').html();
              $empty = $empties.eq(i);
              $empty.removeClass('empty');
              $empty.find('.wrap').html($inner);
              return sizeImages();
            });
          },
          error: function(jqXHR, status, error) {
            return console.log(jqXHR.responseJSON);
          }
        });
      }
    };
    $.fn.scatter = function() {
      var $cells, $grid;
      $grid = $(this);
      $cells = $grid.find('.cell');
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
    $('body').on('click', '#top_bar', closeTopBar);
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
