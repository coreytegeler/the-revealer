jQuery ($) ->
  
  transitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd'
  $carousels = $('.carousel')

  createCarousel = () ->
    $article = $('article.readable')
    $carousel = $('.carousel')
    $slides = $carousel.find('.slides')
    if $article.length
      $imgs = $article.find('img')
      $imgs.each (i, img) ->
        $img = $(img).clone()
        $slide = $('<div class="slide"></div>')
        $wrap = $('<div class="wrap"></div>')
        $wrap.append($img)
        $slide.append($wrap)
        $slides.append($slide)
      setupCarousel()

  openCarousel = (e) ->
    $carousel = $('.carousel')
    $this = $(this)
    if href = $this.attr('href')
      # console.log 'href:', href
      if $this.find('img').length
        isImage = true
    else if src = $this.attr('src')
      # console.log 'src:', src
      isImage = true
    else
      isImage = false

    console.log $carousel
    $carousel.addClass('show')
    if isImage
      e.preventDefault()

  closeCarousel = (e) ->
    $carousel = $(this).parents('.carousel')
    $carousel.removeClass('show')

  resizeCarousel = () ->
    windowWidth = $(window).innerWidth()
    $carousels.each ->
      $carousel = $(this)
      $slides = $carousel.find('.slide')
      slidesLength = $slides.length
      $slidesWrapper = $carousel.find('.slides')
      $currentSlide = $carousel.find('.slide.current')
      currentIndex = $currentSlide.index()
      left = -1 * currentIndex * windowWidth
      # $carousel.css width: windowWidth
      $slidesWrapper.addClass 'static'
      $slidesWrapper.css
        width: slidesLength * windowWidth
        x: left
      $slides.each (i, slide) ->
        imageUrl = $(slide).find('.image').css('backgroundImage')
        if imageUrl
          imageUrl = imageUrl.replace('url(', '').replace(')', '').replace(/"/g, '')
        else 
          return
        image = new Image
        $slide = $(this)
        image.onload = ->
          width = image.width
          height = image.height
          ratio = width / height
          if width >= height
            $slide.addClass 'landscape'
          else
            $slide.addClass 'portrait'
          if !parseInt($slide.css('width'))
            $slide.css width: 'calc(100%/'+slidesLength+')'
          if $slide.is('.current')  
            fixCarouselHeight $slide
        image.src = imageUrl

  fixCarouselHeight = ($slide) ->
    $carousel = $slide.parents('#carousel')
    $caption = $slide.find('.caption')
    captionHeight = $caption.innerHeight()
    minHeight = $carousel.css('content').replace(/['"]+/g,'')
    height =  'calc('+minHeight+' + '+captionHeight+'px)'
    $carousel.transition
      'height': height
    , 200, 'out'

  setupCarousel = () ->
    $('#carousel').each (i, carousel) ->
      $(this).find('.slide:first-child').addClass 'current'
      $(carousel).imagesLoaded ->
        $(carousel).addClass 'loaded'

    $('body').on 'mouseenter', '#carousel.loaded .arrow:not(.no)', ->
      $arrow = $(this)
      direction = $arrow.attr('data-direction')
      $carousel = $arrow.parents('#carousel')
      $carousel.attr('data-direction', direction)

    $('body').on 'mouseleave', '#carousel.loaded .arrow', ->
      $arrow = $(this)
      $carousel = $arrow.parents('#carousel')
      $carousel.attr('data-direction', '')

    $('body').on 'click', '#carousel.loaded .arrow:not(.no)', ->
      $arrow = $(this)
      direction = $arrow.attr('data-direction')
      windowWidth = $(window).innerWidth()
      $carousel = $arrow.parents('#carousel')
      $slidesWrapper = $carousel.find('.slides')
      $currentSlide = $carousel.find('.slide.current')
      currentIndex = $currentSlide.index()
      $firstSlide = $carousel.find('.slide').first()
      $lastSlide = $carousel.find('.slide').last()
      left = parseInt($slidesWrapper.css('left'))
      $slidesWrapper.removeClass 'static'
      switch direction
        when 'left'
          $nextSlide = $currentSlide.prev('.slide')
          left += windowWidth
        when 'right'
          $nextSlide = $currentSlide.next('.slide')
          left -= windowWidth
      if !$nextSlide.length
        switch direction
          when 'left'
            $lastSlide.insertBefore $firstSlide
            $nextSlide = $lastSlide
            $slidesWrapper.addClass 'static'
            currentIndex = $currentSlide.index()
            left = -1 * currentIndex * windowWidth
            $slidesWrapper.css x: left
            left += windowWidth
          when 'right'
            $firstSlide.insertAfter $lastSlide
            $nextSlide = $firstSlide
            $slidesWrapper.addClass 'static'
            currentIndex = $currentSlide.index()
            left = -1 * currentIndex * windowWidth
            $slidesWrapper.css x: left
            left -= windowWidth
        delay = 100
      else
        delay = 0
      fixCarouselHeight($nextSlide)
      setTimeout (->
        $slidesWrapper.removeClass 'static'
        $arrow.addClass 'no'
        $slidesWrapper.stop()
        $currentSlide.removeClass 'current'
        $nextSlide.addClass 'current'
        $slidesWrapper.transition { x: left }, ->
          switch direction
            when 'left'
              $lastSlide.insertBefore $firstSlide
            when 'right'
              $firstSlide.insertAfter $lastSlide
          resizeCarousel()
          $arrow.removeClass 'no'
      ), delay
    resizeCarousel()


  $('body').on('click', 'article.readable a, article.readable img', openCarousel)
  $('body').on('click', '#carousel .close', closeCarousel)

  $ ->
    if $carousels.length
      createCarousel()

  $(window).resize () ->
    setupCarousel()

