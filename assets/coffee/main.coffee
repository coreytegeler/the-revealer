jQuery ($) ->
	$ -> 
		$window = $(window)
		$body = $('body')
		$wrapper = $('#wrapper')
		$side = $('aside')
		$header = $('header')
		$logo = $('#logo')
		$navs = $('nav')
		$mainNav = $('nav.main')
		$mainNavInner = $mainNav.find('.inner')
		$mobileNav = $('nav.mobile')
		$mobileNavInner = $mobileNav.find('.inner')
		$main = $('main')
		$footer = $('footer')
		$topBar = $('#top_bar')
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
					if $img.length
						src = $img.attr('src')
					else
						src = $image.attr('data-src')
					image = new Image()
					image.onload = (e) ->
						img = e.target
						src = img.src
						natWidth = img.naturalWidth
						natHeight = img.naturalHeight
						ratio = natWidth/natHeight
						imageWidth = $image.innerWidth()
						imageHeight = imageWidth/ratio
						if $img.length
							$image.css
								width: imageWidth,
								height: imageHeight
						loadImage($image)
					image.src = src

		loadImage = ($image) ->		
			$img = $image.find('img')
			src = $img.attr('data-src')
			if !$img.length
				src = $image.attr('data-src')
			$image.imagesLoaded {background: true}, () ->
				$image.removeClass('loading').addClass('loaded')
				if $img.length
					if $img.parents('aside').length
						fixSide()
					else
						$image.attr('style','')
						fixGrids()
				setTimeout () ->
					if $grid = $image.parents('.grid')
						$grid.masonry()
			if $img.length
				$img.attr('src', src)
			else
				$image.css
					backgroundImage: 'url('+src+')'


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
			if !$side.length
				return
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
			scrollHeight = $body[0].scrollHeight
			pageHeight = $main.innerHeight()
			pageTop = $main.position().top
			pageBottom = pageTop + pageHeight
			pageEnd = pageBottom - winHeight
			isBottom = (winScroll >= pageEnd)

			if topBarHeight = $topBar.innerHeight()
				pageTop = topBarHeight
			else
				pageTop = 0

			$navs.each (i, nav) ->
				$nav = $(nav)
				$mainNavInner = $nav.find('.inner')
				navBottom = $nav.offset().top + $nav.innerHeight()
				innerHeight = $mainNavInner.innerHeight()
				innerTop = navBottom - innerHeight
				if isBottom
					$wrapper.addClass('bottom')
					$mainNav.css
						y: pageEnd - winScroll
				else
					$mainNav.css
						y: 0
					if $topBar.length
						top = topBarHeight
						winScroll += topBarHeight
					else
						top = 0
					$wrapper.removeClass('bottom')

			# PROGRESS BAR
			$('.prog_bar').each (i, bar) ->
				$bar = $(this)
				barWidth = $(bar).innerWidth()
				progress = winScroll * barWidth / pageEnd
				if isBottom
					progress = barWidth
				$bar.find('.solid').css
					width: progress


			nearBottom = winScroll + winHeight >= scrollHeight - winHeight * 2
			if $readable.is('#discover') && nearBottom
				queryMore()

			
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

		reveal = () ->
			$('.reveal').each (i, elem) ->
				delay = (Math.random() * (50 - 20) + 20) + (i*20)
				setTimeout () ->
					$(elem).addClass('show')
				, delay

		transport = (e) ->
			$button = $(this)
			if isMobile()
				$nav = $mobileNav
			else
				$nav = $mainNav
			scrollTop = $('html,body').scrollTop()
			navBottom = $nav.innerHeight()
			if $button.is('.top')
				scrollTo = 0
			else if $button.is('.ftn')
				e.preventDefault()
				href = $button.attr('href').replace('#', '')
				$inline = $('a').filter('[name="'+href+'"]')
			else
				$image = $button.find('.image')
				$img = $image.find('img')
				src = $img.attr('src')
				if $readable = $('.readable')
					$inline = $readable.find('img').filter('[src="'+src+'"]')

			hasOffset = $inline && $inline.length
			
			if hasOffset
				scrollTo = $inline.offset().top - scrollTop - navBottom*2

			if !isNaN(scrollTo)
				$('html,body').animate
					scrollTop: scrollTo
				, () ->
					if hasOffset
						$inline.focus()


		fixHeader = () ->
			if isMobile()
				$mobileNav.css
					height: Math.ceil($mobileNavInner.innerHeight())
			else
				topHeight = Math.ceil($mainNav.innerHeight())
				$wrapper.css
					paddingTop: topHeight
				$side.css
					height: $window.innerHeight() - topHeight,
					top: topHeight
				taglineHeight = $mainNavInner.find('.tagline').innerHeight()
				linksHeight = $mainNavInner.find('.links').innerHeight()
				taglineWidth = $mainNavInner.find('.tagline').innerWidth()
				linksWidth = $mainNavInner.find('.links').innerWidth()
				# console.log taglineWidth + linksWidth, $mainNav.innerWidth()
				# if linksHeight >= taglineHeight - 5 && linksHeight <= taglineHeight + 5
				# 	$mainNavInner.removeClass('break')
				# else
				# 	$mainNavInner.addClass('break')
			# if !$topBar
				# return
			# topBarHeight = $topBar.innerHeight()

			# $wrapper.css
				# paddingTop: topBarHeight
			# $side.css
			# 	paddingTop: topBarHeight
			# 	height: $window.innerHeight() - topBarHeight

		closeTopBar = () ->
			$topBar.remove()
			fixHeader()
			trackScroll()

		isMobile = () ->
			return parseInt($('#isMobile').css('content').replace(/['"]+/g, ''))

		queryMore = () ->
			$grid = $('.discover.grid')
			if !$grid.is('.querying')
				i = 0
				while i < 15
					$cell = $('<div class="cell discover reveal empty"><div class="wrap"><div class="circle"></div></div></div>')
					$grid.append($cell)
					$grid.masonry('appended', $cell)
					$grid.masonry()
					i++
				$grid.scatter()
				reveal()
				$grid.addClass('querying')
				$.ajax
					url: wp_api.ajax_url,
					type: 'POST',
					data:
						action: 'api_query',
						orderby: 'rand',
						post_type: 'post',
						posts_per_page: 15
						meta_query:
							key: '_thumbnail_id',
							compare: 'EXISTS'
					success: (data, status, jqXHR) ->
						$grid.removeClass('querying')
						$empties = $grid.find('.empty.cell')
						$(data).each (i, cell) ->
							$inner = $(cell).find('.wrap').html()
							$empty = $empties.eq(i)
							$empty.removeClass('empty')
							$empty.find('.wrap').html($inner)
							sizeImages()


					error: (jqXHR, status, error) ->
						console.log jqXHR.responseJSON


		$.fn.scatter = () ->
			$grid = $(this)
			$cells = $grid.find('.cell')
			$cells.filter(':not(.scattered)').each (i, cell) ->
				$cell = $(this)
				$wrap = $cell.find('.wrap')
				padding = parseInt($wrap.css('padding'))
				max = padding
				min = -max
				x = Math.random() * (max - min) + min
				y = Math.random() * (max - min) + min
				$wrap.css
					x: x,
					y: y
				$cell.addClass('scattered')

		$('.glisten').each (ri, html) ->
			ri++
			$html = $(html)
			characters = $html.text().split('')
			$html.empty()
			$(characters).each (ci, html) ->
				$span = $('<span>' + html + '</span>')
				$html.append($span)
			setTimeout () ->
				$html.find('span').each (si, span) ->
					si++
					setTimeout () ->
						$(span).addClass('animate')
					, si*50
			, 500*ri

		$('#logo svg path').each (i, path) ->
			setTimeout () ->
				$(path).addClass('animate')
			, i*50


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


		$('body').on('click', '.transport', transport)
		$('body').on('click', '#filters .toggle', toggleFilterList)
		$('body').on('click', '#top_bar', closeTopBar)
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
		reveal()

