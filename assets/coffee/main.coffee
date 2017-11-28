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
		$popup = $('#popup')
		siteUrl = $body.attr('data-site-url')
		themeUrl = siteUrl+'/wp-content/themes/therevealer'
		assetsUrl = themeUrl+'/assets/'
		sizeImages = (images) ->
			if images
				$images = $(images)
			else
				$images = $('.image.load')	
			$images.each (i, image) ->
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

					src = src.replace('http://', 'https://')
					image = new Image()
					image.onload = (e) ->
						img = e.target
						src = img.src
						natWidth = img.naturalWidth
						natHeight = img.naturalHeight
						ratio = natWidth/natHeight
						imageWidth = $image.innerWidth()
						imageHeight = imageWidth/ratio
						$image.width(imageWidth)
						$image.height(imageHeight)
						$image.css
							width: imageWidth,
							height: imageHeight
						loadImage($image)
					image.onerror = (e) ->
						img = e.target
						loadImage($image)
					image.src = src
				if $masonry = $image.parents('.masonry')
					$masonry.masonry()

			$('article.readable .wp-caption').each (i, elem) ->
				$img = $(elem).find('img')
				$caption = $(elem).find('.wp-caption-text')
				imgWidth = $img.innerWidth()
				if !imgWidth
					imgWidth = 'auto'
				$caption.css
					width: imgWidth

		loadImage = (image) ->
			$image = $(image)
			$cell = $image.parents('.cell')
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
							$image.width('')
							$image.height('')
							if $loop = $img.parents('.masonry:eq(0)')
								fixLoops($loop)
					setTimeout () ->
						if $masonry = $image.parents('.masonry')
							$masonry.masonry()
					if $cell.length
						$cell.addClass('show')
				.fail (instance) ->
					$(instance.elements).each () ->
						$cell = $(this).parents('.cell')
						if $cell.length
							$missing = $cell
						else
							$missing = $(this)
						$missing.addClass('missing')
						$missingSvg = $('#missingSvg svg')
						$missing.html($missingSvg)
						if $cell.length
							$cell.addClass('show')
					if $loop = $img.parents('.masonry:eq(0)')
						fixLoops($loop)
			if $img.length
				$img.attr('src', src)
			else
				$image.css
					backgroundImage: 'url('+src+')'


		fixLoops = ($loops, $cells) ->
			if !$loops
				$loops = $('.loop, .masonry')
			$loops.each () ->
				$loop = $(this)
				isMasonry = $loop.is('.masonry')
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
				if $cells && $cells.length
					if isMasonry
						$loop.masonry('appended', $cells)
					else
						$loop.append($cells)


		sideScroll = null
		lastSideScroll = null
		fixSide = (e) ->
			if !$side.length
				return
			winHeight = $window.innerHeight()
			winScroll = $window.scrollTop()
			pageHeight = $main.outerHeight()
			pageTop = $main.position().top
			pageBottom = pageTop + pageHeight
			pageEnd = pageBottom - winHeight
			isBottom = (winScroll >= pageEnd)
			mainRemain = pageEnd - winScroll

			headerHeight = Math.ceil($header.outerHeight())
			if mainRemain > 0
				sideHeight = $window.innerHeight() - headerHeight
				$side.css
					y: 0
			else
				sideHeight = '100%'
				$side.css
					y: mainRemain

			$side.css
				height: sideHeight,
				top: headerHeight

			# sideTop = $side.position().top
			# sideHeight = $side.innerHeight()
			# sideScroll = $side.scrollTop()
			# sideScrollHeight = $side[0].scrollHeight
			# sideScrolled = sideHeight + sideScroll
			# sideRemain = sideScrollHeight - sideScrolled
			# scrollDiff = sideRemain - mainRemain
			# nextSideScroll = scrollDiff

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
				
			if $body.is('.discover')
				if winScroll + winHeight >= scrollHeight - winHeight * 2
					queryMore()
			else
				$nav = $header.find('nav')
				headerBottom = $header.offset().top + $header.innerHeight()
				innerHeight = $nav.outerHeight()
				if isBottom
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
					# $header.find('.bar').each (i, bar) ->
					# 	$bar = $(this)
					# 	if $bar.is('.prog')
					# 		barWidth = $(bar).innerWidth()
					# 		progress = winScroll * barWidth / pageEnd
					# 		$bar.find('.solid').css
					# 			width: progress

				
			belowThresh = pageEnd/4 - winScroll <= 0
			if $popup.length && !$popup.is('.stuck')
				if winScroll - $popup.innerHeight() - $header.innerHeight() > pageEnd
					console.log 1
					$popup.addClass('show stuck').removeClass('fixed')
					localStorage.setItem('showedPopup', 'true')
					$popup.transition
						y: 0,
					, 0
				else if belowThresh && !$popup.is('.stuck, .fixed')
					console.log 2
					$popup.addClass('show fixed').removeClass('stuck')
					localStorage.setItem('showedPopup', 'true')
					$popup.transition
						y: -$popup.innerHeight()
					, 250
				# else if poppedUp()
				# 	console.log 3
				# 	$popup.addClass('show stuck')
				# else if !$popup.is('.fixed')
				# 	console.log 4
				# 	$popup.removeClass('show')

			fixSide(e)

		poppedUp = () ->
			if localStorage.getItem('showedPopup') == 'true'
				return true
			else
				return false

		toggleHeight = () ->
			$toggle = $(this)
			$listWrap = $toggle.parents('.listWrap')
			$list = $listWrap.find('.list')
			$listWrap.toggleClass('show')
			if $listWrap.is('.show')
				height = $list[0].scrollHeight
				$listWrap.css
					maxHeight: height
			else
				$listWrap.attr('style', '')


			


		# alters and appends article body text to make posts more dynamic
		setupArticle = () ->
			if !$body.is('.single-post')
				return
			$article = $('article')
			# wraps inline images to load before showing
			$sideImages = $side.find('.images .loop')
			$sideImages.masonry()
			fixLoops($sideImages)
			inlineImgs = $article.find('.content img')
			hasImages = false
			for inlineImg in inlineImgs
				$inlineImg = $(inlineImg)
				$wpImg = $inlineImg.parents('.aligncenter, .alignleft, .alignright, .wp-caption')
				# if $wpImg.find('a').length
				# 	$wpImg = $wpImg.find('a')
				if $wpImg.length
					$wpImg.addClass('image load')
				else
					$inlineImg.wrap('<div class="shift image load"></div>')

				currentSrc = inlineImg.currentSrc
				$inlineImg.attr('data-src', currentSrc)
				pseudo = new Image()
				pseudo.onload = (e) ->
					img = e.target
					$cell = $('<div class="cell transport"><div class="image load"></div></div>')
					imageWidth = img.naturalWidth
					imageHeight = img.naturalHeight
					$cell.find('.image').append(img)
					$thumb = $cell.find('img')
					$thumb.attr('data-width', imageWidth).attr('data-height', imageHeight)
					fixLoops($sideImages, $cell)
					$cellImage = $cell.find('.image')
					sizeImages($cellImage)
					$sideImages.parents('.images').removeClass('hide')
				pseudo.onerror = (e) ->
					console.log this, e
					# $missing.addClass('missing')
					# $missing.html('<object type="image/svg+xml" data="'+siteUrl+'/assets/images/missing.svg"></object>')
					# console.log $missing
				pseudo.src = currentSrc

			# console.log $sideImages
			sizeImages($article.find('.content .image.load'))

			links = $article.find('a[href]')
			for link in links
				$link = $(link)
				href = $link.attr('href')	
				# add classes to inline citations and footnotes
				if href.includes('#_ftn')
					replace = $link.text()
						.replace('[','')
						.replace(']','')
					$link.text(replace)
					if href.includes('#_ftnref')
						$link.addClass('ref ftn transport')
					else
						$link.addClass('super ftn transport')
				# open external links in new tab
				else if !href.includes(siteUrl)
					$link.attr('target', '_blank')
			$article.addClass('show')

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
					$inline = $readable.find('img').filter('[data-src="'+src+'"]')
			hasOffset = $inline && $inline.length
			
			if hasOffset
				scrollTo = $inline.offset().top - headerHeight - 20

			if !isNaN(scrollTo)
				$('html,body').animate
					scrollTop: scrollTo
				, () ->
					if hasOffset
						$inline.focus()

		hoverCell = () ->
			$cell = $(this).parents('.cell:eq(0)')
			$cell.toggleClass('hover')

		fixHeader = () ->
			topHeight = Math.ceil($header.outerHeight())
			$wrapper.css
				marginTop: topHeight
			$body.addClass('initd')
			$side.addClass('fixed')
			taglineHeight = $nav.find('.tagline').innerHeight()
			linksHeight = $nav.find('.links').innerHeight()
			taglineWidth = $nav.find('.tagline').innerWidth()
			linksWidth = $nav.find('.links').innerWidth()
			

		closeAlert = () ->
			$alert.remove()
			fixHeader()
			trackScroll()

		closePopup = () ->
			$popup.transition
				y: $popup.innerHeight() 
			, 500, () ->
				$popup.removeClass('fixed')
				$popup.addClass('stuck')

		isMobile = () ->
			return parseInt($('#isMobile').css('content').replace(/['"]+/g, ''))

		window.discovered = []
		queryMore = () ->
			$loop = $('.discover.loop')
			if !$main.is('.querying')
				if !discovered.length
					$loop.find('.cell').each (i, cell) ->
						$cell = $(cell)
						id = $cell.attr('data-id')
						discovered.push(id)
				i = 0
				while i < 15
					$cell = $('<div class="cell discover thumb empty"><div class="wrap"><div class="circle"></div></div></div>')
					$loop.append($cell)
					i++
				$main.addClass('querying')
				$.ajax
					url: wp_api.ajax_url,
					type: 'POST',
					data:
						action: 'api_query',
						discovered: discovered
					success: (cells, status, jqXHR) ->
						$main.removeClass('querying')
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
							$image = $empty.find('.image.load')
							if $image.length
								sizeImages($image)
							else
								$empty.addClass('show')

					error: (jqXHR, status, error) ->
						console.log jqXHR.responseJSON

		shareWindow = (e) ->
			e.preventDefault()
			href = this.href
			window.open(href,'popup','width=600,height=600,scrollbars=no,resizable=no')


		setTimeout () ->
			$('.glisten').each (ri, wrap) ->
				ri++
				$wrap = $(wrap)
				characters = $wrap.text().split('')
				$wrap.empty()
				$(characters).each (ci, html) ->
					$span = $('<span>' + html + '</span>')
					$wrap.append($span)
				$spans = $wrap.find('span')
				setTimeout () ->
					$spans.each (si, span) ->
						si++
						$span = $(span)
						setTimeout () ->
							$span.addClass('animate')
						, si*50
					$wrap.addClass('show')
				, 100*ri
			, 100

		$('#logo svg path').each (i, path) ->
			setTimeout () ->
				$(path).addClass('animate')
			, i*50


		$('body').on('click', '.transport', transport)
		$('body').on('click', '.toggle', toggleHeight)
		$('body').on('click', '#alert .close', closeAlert)
		$('body').on('click', '#popup .close', closePopup)
		$('body').on('hover', '.cell .link_wrap', hoverCell)
		$('body').on('click', 'aside .share a.window', shareWindow)
		
		if localStorage.getItem('showedPopup') == 'true'
			$popup.addClass('show stuck')

		if $body.is('.search')
			$('input#searchbox').focus()

		$window.on 'resize', () ->
			fixLoops()
			sizeImages()
			fixHeader()
			trackScroll()
		.resize()

		$window.on 'scroll', (e) ->
			trackScroll(e)
			fixHeader()

		setupArticle()