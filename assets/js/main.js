jQuery(function($) {
  return $(function() {
    var $alert, $body, $footer, $header, $logo, $main, $nav, $navLinks, $side, $window, $wrapper, closeAlert, fixGrids, fixHeader, fixSide, loadImage, scrollToItem, setupArticle, siteUrl, sizeImages, toggleFilterList, trackScroll;
    $window = $(window);
    $body = $('body');
    $wrapper = $('#wrapper');
    $side = $('aside');
    $header = $('header');
    $logo = $('#logo');
    $nav = $('nav');
    $navLinks = $nav.find('.links');
    $main = $('main');
    $footer = $('footer');
    $alert = $('#alert');
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
          fixSide();
        } else {
          $image.attr('style', '');
          fixGrids();
        }
        return setTimeout(function() {
          var $grid;
          if ($grid = $image.parents('.grid')) {
            return $grid.masonry();
          }
        });
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
      winScroll = $window.scrollTop();
      sideHeight = $side.innerHeight();
      sideScrollHeight = $side[0].scrollHeight;
      if (sideScrollHeight > sideHeight) {
        return $side.scrollTop(winScroll);
      }
    };
    trackScroll = function() {
      var $readable, alertHeight, isBottom, linksHeight, linksTop, navBottom, pageBottom, pageEnd, pageHeight, pageTop, top, winHeight, winScroll;
      $readable = $('.readable');
      if (!$readable.length) {
        return;
      }
      winHeight = $window.innerHeight();
      winScroll = $window.scrollTop();
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
      $('.progbar').each(function(i, bar) {
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
      navBottom = $nav.offset().top + $nav.innerHeight();
      linksHeight = $navLinks.innerHeight();
      linksTop = navBottom - linksHeight;
      if (isBottom) {
        $wrapper.addClass('bottom');
        $navLinks.css({
          y: pageEnd - winScroll
        });
      } else {
        $navLinks.css({
          y: 0
        });
        if ($alert.length) {
          top = alertHeight;
          winScroll += alertHeight;
        } else {
          top = 0;
        }
        $wrapper.removeClass('bottom');
        if (winScroll >= linksTop && winScroll >= pageTop) {
          $navLinks.addClass('fixed');
        } else {
          $navLinks.removeClass('fixed');
          top = 0;
        }
        $navLinks.css({
          top: top
        });
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
    scrollToItem = function(e) {
      var $image, $img, $inline, $item, $readable, href, navBottom, scrollTop, src;
      $item = $(this);
      navBottom = $nav.offset().top + $nav.innerHeight();
      if ($item.is('.ftn')) {
        e.preventDefault();
        href = $item.attr('href').replace('#', '');
        $inline = $('a').filter('[name="' + href + '"]');
      } else {
        $image = $item.find('.image');
        $img = $image.find('img');
        src = $img.attr('src');
        if ($readable = $('.readable')) {
          $inline = $readable.find('img').filter('[src="' + src + '"]');
        }
      }
      if ($inline.length) {
        scrollTop = $inline.offset().top - navBottom;
        return $('html,body').animate({
          scrollTop: scrollTop
        }, function() {
          return $inline.focus();
        });
      }
    };
    fixHeader = function() {
      var alertHeight;
      $nav.css({
        height: Math.ceil($logo.innerHeight())
      });
      if (!$alert) {
        return;
      }
      alertHeight = $alert.innerHeight();
      $wrapper.css({
        paddingTop: alertHeight
      });
      return $side.css({
        paddingTop: alertHeight,
        height: $window.innerHeight() - alertHeight
      });
    };
    closeAlert = function() {
      $alert.remove();
      fixHeader();
      return trackScroll();
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
        max = padding * .8;
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
    $('body').on('click', '.transport', scrollToItem);
    $('body').on('click', '#filters .toggle', toggleFilterList);
    $('body').on('click', '#alert', closeAlert);
    $window.on('resize', function() {
      fixGrids();
      sizeImages();
      fixHeader();
      return trackScroll();
    }).resize();
    $window.on('scroll', function() {
      return trackScroll();
    });
    return setupArticle();
  });
});
