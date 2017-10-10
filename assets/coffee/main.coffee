jQuery ($) ->
	$ -> 
		$window = $(window)
		$body = $('body')
		$wrapper = $('#wrapper')
		$side = $('aside')
		$header = $('header')
		$logo = $('#logo')
		$headers = $('header')
		$header = $('header.main')
		$nav = $header.find('nav')
		$main = $('main')
		$footer = $('footer')
		$alert = $('#alert')
		siteUrl = $body.attr('data-site-url')

		sizeImages = () ->
			$('.image.load').each (i, image) ->
				$image = $(image)
				$img = $image.find('img')
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
				else if $image.parents('.cell.discover').length
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
						$image.css
							width: imageWidth,
							height: imageHeight
						loadImage($image)
					image.onerror = (e) ->
						img = e.target
						loadImage($image)
					image.src = src

		loadImage = (image) ->
			$image = $(image)
			if !$image.is('.load')
				return
			$image.removeClass('load').addClass('loading')
			$img = $image.find('img')
			src = $img.attr('data-src')
			if !$img.length
				src = $image.attr('data-src')
			$image.imagesLoaded {background: true}
				.done (instance) ->
					$image.removeClass('loading').addClass('loaded')
					if $img.length
						if $img.parents('aside').length
							fixSide()
						else
							$image.attr('style','')
							fixGrids()
					setTimeout () ->
						if $masonry = $image.parents('.masonry')
							$masonry.masonry()
				.fail (instance) ->
					$(instance.elements).each () ->
						$cell = $(this).parents('.cell')
						if $cell.length
							$cell.remove()
						else
							$(this).remove()
					fixGrids()
			if $img.length
				$img.attr('src', src)
			else
				$image.css
					backgroundImage: 'url('+src+')'


		fixGrids = ($loops, $cells) ->
			if !$loops
				$loops = $('.loop')
			$loops.each () ->
				$loop = $(this)
				isMasonry = $loop.is('.masonry')
				if $cells
					if isMasonry
						$loop.masonry()
					$loop.append($cells)
					if isMasonry
						$loop.masonry('appended', $cells)
				$first = $loop.find('.cell:eq(0)')
				if !$first.length
					return
				columnWidth = parseInt($first.css('width'))
				gutter = parseInt($first.css('marginBottom'))
				if isMasonry
					$loop.masonry
						itemSelector: '.cell',
						columnWidth: $first[0],
						gutter: gutter,
						transitionDuration: 0,
						percentPosition: true,
						fitWidth: true

		fixSide = (e) ->
			if !$side.length
				return
			winHeight = $window.innerHeight()
			winScroll = $window.scrollTop()
			pageHeight = $main.innerHeight()
			pageTop = $main.position().top
			pageBottom = pageTop + pageHeight
			pageEnd = pageBottom - winHeight
			isBottom = (winScroll >= pageEnd)

			sideHeight = $side.innerHeight()
			sideScrollHeight = $side[0].scrollHeight
			sideScroll = $side.scrollTop()

		trackScroll = (e) ->
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

			if alertHeight = $alert.innerHeight()
				pageTop = alertHeight
			else
				pageTop = 0
				
			$headers.each (i, header) ->
				$header = $(header)
				$nav = $header.find('nav')
				headerBottom = $header.offset().top + $header.innerHeight()
				innerHeight = $nav.outerHeight()
				if isBottom
					sideScroll = $side.scrollTop()
					$wrapper.addClass('bottom')
					$header.css
						y: pageEnd - winScroll
				else
					$header.css
						y: 0
					if $alert.length
						top = alertHeight
						winScroll += alertHeight
					else
						top = 0
					$wrapper.removeClass('bottom')
				# PROGRESS BAR
				$header.find('.bar').each (i, bar) ->
					$bar = $(this)
					if $bar.is('.prog')
						barWidth = $(bar).innerWidth()
						progress = winScroll * barWidth / pageEnd
						$bar.find('.solid').css
							width: progress
					# else if $bar.is('.bg')
					# 	opacity = winScroll * 1 / 20
					# 	$bar.css
					# 		opacity: opacity

			if $side.length
				sideTop = $side.position().top
				sideHeight = $side.innerHeight()
				sideBottom = sideTop + sideHeight
				sideScroll = $side.scrollTop()
				sideEnd = sideBottom - $side.innerHeight()
				scrollPast = winScroll - pageEnd
				notSideBottom = sideScroll < $side[0].scrollHeight - sideHeight
				if notSideBottom
					$side.scrollTop(scrollPast)

			nearBottom = winScroll + winHeight >= scrollHeight - winHeight * 2
			if $readable.is('#discover') && nearBottom
				queryMore()
			fixSide(e)

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
			inlineImgs = $article.find('.content img')
			for inlineImg in inlineImgs
				$(inlineImg).wrap('<div class="image load"></div>')
		
			sizeImages()

			links = $article.find('a[href]')
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
			$header = $header
			scrollTop = $('html,body').scrollTop()
			headerHeight = $header.innerHeight()
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
				scrollTo = $inline.offset().top - headerHeight - 10

			if !isNaN(scrollTo)
				$('html,body').animate
					scrollTop: scrollTo
				, () ->
					if hasOffset
						$inline.focus()

		hoverCell = () ->
			$cell = $(this).parents('.cell')
			$cell.toggleClass('hover')

		fixHeader = () ->
			topHeight = Math.ceil($header.outerHeight())
			$wrapper.css
				paddingTop: topHeight
			$side.css
				height: $window.innerHeight() - topHeight,
				top: topHeight
			taglineHeight = $nav.find('.tagline').innerHeight()
			linksHeight = $nav.find('.links').innerHeight()
			taglineWidth = $nav.find('.tagline').innerWidth()
			linksWidth = $nav.find('.links').innerWidth()
				# console.log taglineWidth + linksWidth, $header.innerWidth()
				# if linksHeight >= taglineHeight - 5 && linksHeight <= taglineHeight + 5
				# 	$nav.removeClass('break')
				# else
				# 	$nav.addClass('break')
			# if !$alert
				# return
			# alertHeight = $alert.innerHeight()

			# $wrapper.css
				# paddingTop: alertHeight
			# $side.css
			# 	paddingTop: alertHeight
			# 	height: $window.innerHeight() - alertHeight

		closeAlert = () ->
			$alert.transition
				height: 0
			, 900, () ->
				$alert.remove()
				fixHeader()
				trackScroll()

		isMobile = () ->
			return parseInt($('#isMobile').css('content').replace(/['"]+/g, ''))

		window.discovered = []
		queryMore = () ->
			$loop = $('.discover.loop')
			
			if !$loop.is('.querying')
				if !discovered.length
					$loop.find('.cell').each (i, cell) ->
						$cell = $(cell)
						id = $cell.attr('data-id')
						discovered.push(id)

				i = 0
				while i < 15
					$cell = $('<div class="cell discover reveal thumb show empty"><div class="wrap"><div class="circle"></div></div></div>')
					$loop.append($cell)
					i++
				reveal()
				$loop.addClass('querying')
				$.ajax
					url: wp_api.ajax_url,
					type: 'POST',
					data:
						action: 'api_query',
						discovered: discovered
					success: (cells, status, jqXHR) ->
						$loop.removeClass('querying')
						$empties = $loop.find('.empty.cell')
						$empties.each (i, empty) ->
							$empty = $(empty)
							$cell = $(cells).eq(i)
							id = $cell.attr('data-id')
							discovered.push(id)
							if $cell.length
								$inner = $cell.find('.wrap').html()
								$empty.removeClass('empty')
								$empty.find('.wrap').html($inner)
							# else
								# $empty.remove()
							sizeImages()

					error: (jqXHR, status, error) ->
						console.log jqXHR.responseJSON


		$.fn.scatter = () ->
			$masonry = $(this)
			$cells = $masonry.find('.cell')
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
		$('body').on('click', '#alert .close', closeAlert)
		$('body').on('hover', '.cell .link_wrap', hoverCell)
		# $('.single article .super').on 'click', scrollToFootnote

		$window.on 'resize', () ->
			fixGrids()
			sizeImages()
			fixHeader()
			trackScroll()
		.resize()


		$window.on 'scroll', (e) ->
			trackScroll(e)

		setupArticle()
		reveal()

