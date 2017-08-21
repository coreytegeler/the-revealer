jQuery ($) ->
	$ -> 
		$window = $(window)
		$body = $('body')
		$side = $('aside')
		$header = $('header')
		$logo = $('#logo')
		$nav = $('nav')
		$footer = $('footer')
		

		sizeImages = () ->
			$('.image.load').each () ->
				$image = $(this)
				$img = $image.find('img')
				$image.removeClass('load').addClass('loading')
				if $img.parents('article')
					$parent = $img.parents('article')
				else
					$image = $img.parents('.image')
				natWidth = $img.attr('data-width')
				natHeight = $img.attr('data-height')

				if natWidth && natHeight
					ratio = natWidth/natHeight
					imageWidth = $image.innerWidth()
					imageHeight = imageWidth/ratio
					$image.css
						width: imageWidth,
						height: imageHeight
					loadImage($image)
				else
					src = $img.attr('src')
					image = new Image()
					image.onload = (e) ->
						img = e.target
						src = img.src
						natWidth = img.naturalWidth
						natHeight = img.naturalHeight
						ratio = natWidth/natHeight
						imageWidth = $image.innerWidth()
						imageHeight = imageWidth/ratio
						$image.css
							width: imageWidth,
							height: imageHeight
						loadImage($image)
					image.src = src

		loadImage = ($image) ->		
			$img = $image.find('img')
			src = $img.data('src')
			$image.imagesLoaded().progress () ->
				$image.removeClass('loading').addClass('loaded')
				if $img.parents('aside').length
					fixSide()
				else
					$image.attr('style','')
					fixGrids()
			$img.attr('src', src)


		fixGrids = ($grids, $cells) ->
			if !$grids
				$grids = $('.grid')
			$grids.each () ->
				$grid = $(this)
				if $cells
					$grid.masonry()
						.append($cells)
						.masonry('appended', $cells)
				$first = $grid.find('.cell:eq(0)')
				if !$first.length
					return
				columnWidth = parseInt($first.css('width'))
				gutter = parseInt($first.css('marginBottom'))
				$grid.masonry
					itemSelector: '.cell',
					columnWidth: columnWidth,
					gutter: gutter,
					transitionDuration: 0,
					percentPosition: true

		fixSide = () ->
			winScroll = $window.scrollTop()
			sideHeight = $side.innerHeight()
			sideScrollHeight = $side[0].scrollHeight
			if sideScrollHeight > sideHeight
				$side.scrollTop(winScroll)


		trackScroll = () ->
			$readable = $('.readable')
			if !$readable.length
				return
			winWidth = $window.innerWidth()
			winHeight = $window.innerHeight()
			winScroll = $window.scrollTop()
			headerHeight = $header.innerHeight()
			pageHeight = $readable.innerHeight()
			pageTop = $readable.position().top
			pageBottom = pageTop + pageHeight
			pageEnd = pageBottom - winHeight
			progress = (winScroll - headerHeight) * winWidth / pageEnd
			# opacity = (winScroll * 100 / pageEnd)/100
			$('.bar').each (i, bar) ->
				$bar = $(this)
				$bar.find('.solid').css
					width: progress

				headerHeight = $header.innerHeight()
				headerOpacity = winScroll * 1 / headerHeight

				if $bar.is('.top')
					topY = $bar.position().top
					if topY < winScroll
						$bar.addClass('fixed')
					else
						$bar.removeClass('fixed')
			
			fixSide()

		gatherArticleImages = () ->
			return
			if $body.is('.single')
				$images = $side.find('.images')
				$article = $('article')
				imgs = $article.find('.content img')
				for img in imgs
					src = img.src
					thumb = new Image()
					thumb.onload = (e) ->
						img = e.target
						src = img.src
						$img = $(img)
						natWidth = img.naturalWidth
						natHeight = img.naturalHeight
						$img = $('<img/>')
							.attr('src', src)
							.attr('data-width', natWidth)
							.attr('data-height', natHeight)
						$image = $('<div class="image"></div>')
							.append($img)
							.addClass('load')
							.addClass('cell')
						fixGrids($images, $image)
						sizeImages()
					thumb.src = src


		$window.on 'resize', () ->
			trackScroll()
			fixGrids()
			sizeImages()
			$nav.css
				height: $logo.innerHeight()
		.resize()


		$window.on 'scroll', () ->
			trackScroll()

		gatherArticleImages()

