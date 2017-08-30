jQuery ($) ->
	$ -> 
		$window = $(window)
		$body = $('body')
		$wrapper = $('#wrapper')
		$side = $('aside')
		$header = $('header')
		$logo = $('#logo')
		$nav = $('nav')
		$navLinks = $nav.find('.links')
		$main = $('main')
		$footer = $('footer')
		$alert = $('#alert')
		siteUrl = $body.attr('data-site-url')

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
				setTimeout () ->
					if $grid = $image.parents('.grid')
						$grid.masonry()
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
				if $grid.is('.discovery')
					$grid.scatter()
				$first = $grid.find('.cell:eq(0)')
				if !$first.length
					return
				columnWidth = parseInt($first.css('width'))
				gutter = parseInt($first.css('marginBottom'))
				$grid.masonry
					itemSelector: '.cell',
					columnWidth: $first[0],
					gutter: gutter,
					transitionDuration: 0,
					percentPosition: true,
					fitWidth: true

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
			winHeight = $window.innerHeight()
			winScroll = $window.scrollTop()
			pageHeight = $main.innerHeight()
			pageTop = $main.position().top
			pageBottom = pageTop + pageHeight
			pageEnd = pageBottom - winHeight
			isBottom = (winScroll >= pageEnd)

			if alertHeight = $alert.innerHeight()
				pageTop = alertHeight
			else
				pageTop = 0

			$('.progbar').each (i, bar) ->
				$bar = $(this)
				barWidth = $(bar).innerWidth()
				progress = winScroll * barWidth / pageEnd
				if isBottom
					progress = barWidth
				$bar.find('.solid').css
					width: progress


			
			navBottom = $nav.offset().top + $nav.innerHeight()
			linksHeight = $navLinks.innerHeight()
			linksTop = navBottom - linksHeight
			
			

			if isBottom
				$wrapper.addClass('bottom')
				$navLinks.css
					y: pageEnd - winScroll
			else
				$navLinks.css
					y: 0
				if $alert.length
					top = alertHeight
					winScroll += alertHeight
				else
					top = 0

				$wrapper.removeClass('bottom')
				if(winScroll >= linksTop && winScroll >= pageTop)
					$navLinks.addClass('fixed')
				else
					$navLinks.removeClass('fixed')
					top = 0
				$navLinks.css
					top: top

			fixSide()

		toggleFilterList = () ->
			$toggle = $(this)
			$filter = $toggle.parents('.filter')
			$list = $filter.find('.list')
			$list.toggleClass('show')

			if $list.is('.show')
				height = $list[0].scrollHeight
			else
				height = 0
			$list.css
				height: height

		# alters and appends article body text to make posts more dynamic
		setupArticle = () ->
			if !$body.is('.single-post')
				return
			$article = $('article')

			# wraps inline images to load before showing
			$images = $side.find('.images')
			imgs = $article.find('.content img')
			for img in imgs
				$(img).wrap('<div class="image load"></div>')
				loadImage($(img).parents('.image'))

			links = $article.find('a')
			for link in links
				$link = $(link)
				href = $link.attr('href')	
				# adds classes to inline citations and footnotes
				if href.includes('#_ftn')
					replace = $link.text()
						.replace('[','')
						.replace(']','')
					$link.text(replace)
					if href.includes('#_ftnref')
						$link.addClass('ref ftn transport')
					else
						$link.addClass('super ftn transport')
				# makes external links open in new tab
				else if !href.includes(siteUrl)
					$link.attr('target', '_blank')
			$article.addClass('show')


		scrollToItem = (e) ->
			$item = $(this)
			navBottom = $nav.offset().top + $nav.innerHeight()
			if $item.is('.ftn')
				e.preventDefault()
				href = $item.attr('href').replace('#', '')
				$inline = $('a').filter('[name="'+href+'"]')
			else
				$image = $item.find('.image')
				$img = $image.find('img')
				src = $img.attr('src')
				if $readable = $('.readable')
					$inline = $readable.find('img').filter('[src="'+src+'"]')

			if $inline.length
				scrollTop = $inline.offset().top - navBottom
				$('html,body').animate
					scrollTop: scrollTop
				, () ->
					$inline.focus()


		fixHeader = () ->
			$nav.css
				height: Math.ceil($logo.innerHeight())
			if !$alert
				return
			alertHeight = $alert.innerHeight()
			$wrapper.css
				paddingTop: alertHeight
			$side.css
				paddingTop: alertHeight
				height: $window.innerHeight() - alertHeight

		closeAlert = () ->
			$alert.remove()
			fixHeader()
			trackScroll()

		$.fn.scatter = () ->
			$grid = $(this)
			$cells = $grid.find('.cell')
			$cells.filter(':not(.scattered)').each (i, cell) ->
				$cell = $(this)
				$wrap = $cell.find('.wrap')
				padding = parseInt($wrap.css('padding'))
				max = padding*.8
				min = -max
				x = Math.random() * (max - min) + min
				y = Math.random() * (max - min) + min
				$wrap.css
					x: x,
					y: y
				$cell.addClass('scattered')


		# gatherArticleImages = () ->
		# 	return
		# 	if $body.is('.single')
		# 		$images = $side.find('.images')
		# 		$article = $('article')
		# 		imgs = $article.find('.content img')
		# 		for img in imgs
		# 			src = img.src
		# 			thumb = new Image()
		# 			thumb.onload = (e) ->
		# 				img = e.target
		# 				src = img.src
		# 				$img = $(img)
		# 				natWidth = img.naturalWidth
		# 				natHeight = img.naturalHeight
		# 				$img = $('<img/>')
		# 					.attr('src', src)
		# 					.attr('data-width', natWidth)
		# 					.attr('data-height', natHeight)
		# 				$image = $('<div class="image"></div>')
		# 					.append($img)
		# 					.addClass('load')
		# 					.addClass('cell')
		# 				fixGrids($images, $image)
		# 				sizeImages()
		# 			thumb.src = src


		$('body').on('click', '.transport', scrollToItem)
		$('body').on('click', '#filters .toggle', toggleFilterList)
		$('body').on('click', '#alert', closeAlert)
		# $('.single article .super').on 'click', scrollToFootnote

		$window.on 'resize', () ->
			fixGrids()
			sizeImages()
			fixHeader()
			trackScroll()
		.resize()


		$window.on 'scroll', () ->
			trackScroll()

		setupArticle()

