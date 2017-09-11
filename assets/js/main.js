jQuery(function($) {
  return $(function() {
    var $body, $footer, $header, $logo, $main, $mainNav, $mainNavInner, $mobileNav, $mobileNavInner, $navs, $side, $topBar, $window, $wrapper, closeTopBar, fixGrids, fixHeader, fixSide, isMobile, loadImage, queryMore, reveal, setupArticle, siteUrl, sizeImages, toggleFilterList, trackScroll, transport;
    $window = $(window);
    $body = $('body');
    $wrapper = $('#wrapper');
    $side = $('aside');
    $header = $('header');
    $logo = $('#logo');
    $navs = $('nav');
    $mainNav = $('nav.main');
    $mainNavInner = $mainNav.find('.inner');
    $mobileNav = $('nav.mobile');
    $mobileNavInner = $mobileNav.find('.inner');
    $main = $('main');
    $footer = $('footer');
    $topBar = $('#top_bar');
    siteUrl = $body.attr('data-site-url');
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
            if ($img.length) {
              $image.css({
                width: imageWidth,
                height: imageHeight
              });
            }
            return loadImage($image);
          };
          return image.src = src;
        }
      });
    };
    loadImage = function($image) {
      var $img, src;
      $img = $image.find('img');
      src = $img.attr('data-src');
      if (!$img.length) {
        src = $image.attr('data-src');
      }
      $image.imagesLoaded({
        background: true
      }, function() {
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
        if ($grid.is('.discovery')) {
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
    fixSide = function() {
      var sideHeight, sideScrollHeight, winScroll;
      if (!$side.length) {
        return;
      }
      winScroll = $window.scrollTop();
      sideHeight = $side.innerHeight();
      sideScrollHeight = $side[0].scrollHeight;
      if (sideScrollHeight > sideHeight) {
        return $side.scrollTop(winScroll);
      }
    };
    trackScroll = function() {
      var $readable, isBottom, nearBottom, pageBottom, pageEnd, pageHeight, pageTop, scrollHeight, topBarHeight, winHeight, winScroll;
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
      $navs.each(function(i, nav) {
        var $nav, innerHeight, innerTop, navBottom, top;
        $nav = $(nav);
        $mainNavInner = $nav.find('.inner');
        navBottom = $nav.offset().top + $nav.innerHeight();
        innerHeight = $mainNavInner.innerHeight();
        innerTop = navBottom - innerHeight;
        if (isBottom) {
          $wrapper.addClass('bottom');
          return $mainNav.css({
            y: pageEnd - winScroll
          });
        } else {
          $mainNav.css({
            y: 0
          });
          if ($topBar.length) {
            top = topBarHeight;
            winScroll += topBarHeight;
          } else {
            top = 0;
          }
          return $wrapper.removeClass('bottom');
        }
      });
      $('.prog_bar').each(function(i, bar) {
        var $bar, barWidth, progress;
        $bar = $(this);
        barWidth = $(bar).innerWidth();
        progress = winScroll * barWidth / pageEnd;
        if (isBottom) {
          progress = barWidth;
        }
        return $bar.find('.solid').css({
          width: progress
        });
      });
      nearBottom = winScroll + winHeight >= scrollHeight - winHeight * 2;
      if ($readable.is('#discover') && nearBottom) {
        queryMore();
      }
      return fixSide();
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
      var $article, $images, $link, href, img, imgs, j, k, len, len1, link, links, replace;
      if (!$body.is('.single-post')) {
        return;
      }
      $article = $('article');
      $images = $side.find('.images');
      imgs = $article.find('.content img');
      for (j = 0, len = imgs.length; j < len; j++) {
        img = imgs[j];
        $(img).wrap('<div class="image load"></div>');
        loadImage($(img).parents('.image'));
      }
      links = $article.find('a');
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
      var $button, $image, $img, $inline, $nav, $readable, hasOffset, href, navBottom, scrollTo, scrollTop, src;
      $button = $(this);
      if (isMobile()) {
        $nav = $mobileNav;
      } else {
        $nav = $mainNav;
      }
      scrollTop = $('html,body').scrollTop();
      navBottom = $nav.innerHeight();
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
        scrollTo = $inline.offset().top - scrollTop - navBottom * 2;
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
      if (isMobile()) {
        return $mobileNav.css({
          height: Math.ceil($mobileNavInner.innerHeight())
        });
      } else {
        topHeight = Math.ceil($mainNav.innerHeight());
        $wrapper.css({
          paddingTop: topHeight
        });
        $side.css({
          height: $window.innerHeight() - topHeight,
          top: topHeight
        });
        taglineHeight = $mainNavInner.find('.tagline').innerHeight();
        linksHeight = $mainNavInner.find('.links').innerHeight();
        taglineWidth = $mainNavInner.find('.tagline').innerWidth();
        return linksWidth = $mainNavInner.find('.links').innerWidth();
      }
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
    $window.on('scroll', function() {
      return trackScroll();
    });
    setupArticle();
    return reveal();
  });
});
