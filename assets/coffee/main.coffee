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
		$seeker = $('.super.seeker')
		$carousel = $('#carousel')
		siteUrl = $body.attr('data-site-url')
		themeUrl = siteUrl+'/wp-content/themes/therevealer'
		assetsUrl = themeUrl+'/assets/'
		transitionEnd = 'transitionend webkitTransitionEnd oTransitionEnd'

		####################################
		##############CAROUSEL##############
		####################################
		createCarousel = () ->
			$article = $('article.readable')
			$slides = $carousel.find('.slides')
			if $article.length
				$imgs = $article.find('img')
				$imgs.each (i, img) ->
					$image = $(img).parents('.image')
					$caption = $image.find('.wp-caption-text')
					$newImg = $(img).clone()
					$(img).attr('data-index',i)
					$slide = $('<div class="slide"></div>')
					$wrap = $('<div class="wrap"></div>')
					$slide.attr('data-index',i)
					$wrap.append($newImg)
					$slide.append($wrap)
					if $caption.length && captionHtml = $caption.html()
						$slide.append('<div class="caption">'+captionHtml+'</div>')
					$slides.append($slide)
					imgSrc = $newImg.attr('src')
					if !imgSrc
						return
					imgSrcEnd = imgSrc.substring(imgSrc.lastIndexOf('-') + 1)
					if !isNaN(parseInt(imgSrcEnd))
						imgExt = imgSrc.substring(imgSrc.lastIndexOf('.') + 1)
						fullImgSrc = imgSrc.replace('-'+imgSrcEnd, '') + '.' + imgExt
						fullImg = new Image
						fullImg.onload = (e) ->
							$(img).attr('data-full', fullImgSrc)
							# new Date().getTime()
							$newImg.attr('src', fullImgSrc)
						fullImg.src = fullImgSrc

				if $imgs.length > 1
					$carousel.addClass('slidable')

		openCarousel = (e) ->
			$carousel.imagesLoaded ->
				$carousel.addClass 'loaded'
			if $carousel.is('.opening')
				e.preventDefault()
				return
			$body.addClass('no_scroll')
			$carousel.addClass('opening')
			$this = $(this)
			href = $this.attr('href')
			src = $this.attr('src')
			if href
				$img = $this.find('img')
				if $img.length
					isImage = true
			else if src
				$img = $this
				isImage = true
			else
				isImage = false

			index = $img.attr('data-index')
			if isImage
				if fullSrc = $img.attr('data-full')
					src = fullSrc
				if $thisSlide = $carousel.find('.slide[data-index="'+index+'"]')
					$carousel.slide(null,$thisSlide)
					$carousel.addClass('show')
					e.preventDefault()

			setTimeout () ->
				$carousel.removeClass('opening')
			, 500

		closeCarousel = (e) ->
			$body.removeClass('no_scroll')
			$carousel.removeClass('show')

		resizeCarousel = () ->
			windowWidth = $(window).innerWidth()
			$slides = $carousel.find('.slide')
			slidesLength = $slides.length
			$slidesWrapper = $carousel.find('.slides')
			$currentSlide = $carousel.find('.slide.current')
			currentIndex = $currentSlide.index()
			# $carousel.css width: windowWidth
			$slidesWrapper.addClass 'static'
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
			$caption = $slide.find('.caption')
			captionHeight = $caption.innerHeight()
			console.log captionHeight
			# minHeight = $carousel.css('content').replace(/['"]+/g,'')
			# height =  'calc('+minHeight+' + '+captionHeight+'px)'
			# $carousel.transition
				# 'height': height
			# , 200, 'out'

		clickCarouselArrow = () ->
			$arrow = $(this)
			direction = $arrow.attr('data-direction')
			$carousel.slide(direction)

		$.fn.slide = (direction, go) ->
			$carousel = $(this)
			$arrow = $carousel.find('.arrow.'+direction)
			windowWidth = $(window).innerWidth()
			$slidesWrapper = $carousel.find('.slides')
			$currentSlide = $carousel.find('.slide.current')
			currentIndex = $currentSlide.index()
			$firstSlide = $carousel.find('.slide').first()
			$lastSlide = $carousel.find('.slide').last()
			$slidesWrapper.removeClass 'static'
			if go
				$nextSlide = $(go)
			else if direction == 'left'
				$nextSlide = $currentSlide.prev('.slide')
				if !$nextSlide.length
					$nextSlide = $lastSlide
			else if direction == 'right'
				$nextSlide = $currentSlide.next('.slide')
				if !$nextSlide.length
					$nextSlide = $firstSlide

			fixCarouselHeight($nextSlide)
			$arrow.addClass 'no'
			$slidesWrapper.stop()
			$currentSlide.removeClass 'current'
			$nextSlide.addClass 'current'
			$arrow.removeClass 'no'


		####################################
		###############IMAGES###############
		####################################

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

					# src = src.replace('http://', 'https://')
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
				$loop = $image.parents('.loop')[0]
				# if $loop.length && $loop.is('.masonry')
				# 		$masonry.masonry()

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
							$missingCell = $cell
						else
							$missingCell = $(this)
						if $missingImg = $missingCell.find('img')
							$missingImg.remove()
						$missingImage = $missingCell.find('.image')
						missingUrl = $('#missingSvg').attr('data-url')
						$missingCell.load missingUrl, null, () ->
							$missingCell.addClass('missing')
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
			if !$readable.length || $body.is('.no_scroll')
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
					setTimeout () ->
						queryMore()
					, 3000
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
				
			belowThresh = pageEnd/4 - winScroll <= 0
			if $popup.length && !$popup.is('.stuck')
				popupObj = JSON.stringify
					shown: true,
					time: new Date().getTime()
				if winScroll - $popup.innerHeight() - $header.innerHeight() > pageEnd
					$popup.addClass('show stuck').removeClass('fixed')
					localStorage.setItem('popup', popupObj)
					$popup.transition
						y: 0,
					, 0
				else if belowThresh && !$popup.is('.stuck, .fixed')
					$popup.addClass('show fixed').removeClass('stuck')
					localStorage.setItem('popup', popupObj)
					$popup.transition
						y: -$popup.innerHeight()
					, 250

			fixSide(e)

		toggleToggler = () ->
			$toggle = $(this)
			data = $toggle.attr('data-toggle')
			$toggler = $('.toggler').filter('[data-toggle="'+data+'"]')
			$intra = $toggler.find('.intra')
			$toggler.toggleClass('toggled')
			if $toggler.is('.toggled')
				height = $intra[0].scrollHeight
				$toggler.css
					maxHeight: height
				if $toggle.is('.filters')
					$('html,body').animate
						scrollTop: 0
			else
				$toggler.attr('style', '')

			$toggle.toggleClass('toggled')

		fixToggler = () ->
			$('.toggler:not(.navigation)').each (i, toggler) ->
				$toggler = $(toggler)
				$inner = $toggler.find('.intra')
				if $inner.innerHeight() <= $toggler.innerHeight() + 5
					$toggler.addClass('no-toggle')
				else
					$toggler.removeClass('no-toggle')

			


		# alters and appends article body text to make posts more dynamic
		setupArticle = () ->
			if !$body.is('.single-post, .page-template-default')
				return
			$article = $('article.readable')
			$content = $article.find('.text .content')
			inlineImgs = $content.find('img')
			hasImages = false
			for inlineImg in inlineImgs
				$inlineImg = $(inlineImg)
				$wpImg = $inlineImg.parents('a, .aligncenter, .alignleft, .alignright, .wp-caption')
				$wpImg.filter('a').removeClass('href')
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
					$cellImage = $cell.find('.image')
					sizeImages($cellImage)
				pseudo.onerror = (e) ->
				pseudo.src = currentSrc

			sizeImages($article.find('.content .image.load'))
			if $carousel.length
				createCarousel()

			links = $article.find('a[href]')
			for link in links
				$link = $(link)
				href = $link.attr('href')	
				# add classes to inline citations and footnotes
				if href.includes('#_ftn') || href.includes('#_edn')
					replace = $link.text()
						.replace('[','')
						.replace(']','')
						.replace('(','')
						.replace(')','')
					$link.text(replace)
					if href.includes('#_ftnref') || href.includes('#_ednref')
						name = href.replace('ref','').replace('#', '')
						$link.attr('name', name).addClass('ref ftn transport')
					else
						name = href.replace('#', '')
						split = name.split(/(\d+)/)
						name = split[0]+'ref'+split[1]
						$link.attr('name', name).addClass('super ftn transport')
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
			windowHeight = $window.innerHeight()
			topHeight = Math.ceil($header.outerHeight())
			$wrapper.css
				marginTop: topHeight
			if $filtersToggle = $('.toggle.filters')
				$filtersToggle.css
					y: topHeight
			if $seeker
				$seeker.css
					height: windowHeight - topHeight
					marginTop: topHeight
			$body.addClass('initd')
			$side.addClass('fixed')
			taglineHeight = $nav.find('.tagline').innerHeight()
			linksHeight = $nav.find('.links').innerHeight()
			taglineWidth = $nav.find('.tagline').innerWidth()
			linksWidth = $nav.find('.links').innerWidth()
			
		toggleSeeker = (e) ->
			$link = $(this)
			title = $link.data('title')
			if title != 'Search'
				return
			e.preventDefault()
			if !$body.hasClass('search')
				$body.toggleClass('no_scroll')
				$('.toggled[data-toggle="nav"]').removeClass('toggled').attr('style', '')
				$seeker.toggleClass('open')
				if $seeker.is('.open')
					$seeker.find('input[type="search"]').focus()
				else 
					$seeker.find('input[type="search"]').blur()
			else
				$seeker = $('.seeker:first-child')
				$seeker.find('input[type="search"]').focus()

		closeSeeker = (e) ->
			$seeker = $('.super.seeker')
			$seeker.removeClass('open')

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
			return parseInt($('#is_mobile').css('content').replace(/['"]+/g, ''))

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
				while i < 50
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

		animateTexts = () ->
			setTimeout () ->
				$('.glisten').each (ri, wrap) ->
					ri++
					$wrap = $(wrap)
					$words = $wrap.find('.word')
					$wrap.empty()
					$words.each ( i, word ) ->
						$wordSpan = $(word)
						word = $wordSpan.text()
						chars = word.split('')
						$wordSpan.empty()
						for char in chars
							$span = $('<span class="char">' + char + '</span>')
							$wordSpan.append($span)
						$wrap.append($wordSpan)

					$spans = $wrap.find('span.char')
					$spans.each (si, span) ->
						animateText(span, si)
					$wrap.addClass('show')
			, 400

		animateText = (html, index) ->
			setTimeout () ->
				$(html).addClass('animate')
			, 50*index

		$('body').on('click touchend', 'article.readable a, article.readable img', openCarousel)
		$('body').on('click touchend', '#carousel .close', closeCarousel)
		$('body').on 'click touchend', '#carousel.loaded.slidable .arrow:not(.no)', clickCarouselArrow

		$('body').on('click touchend', '.transport', transport)
		$('body').on('click touchend', '.toggle[data-toggle]', toggleToggler)
		$('body').on('click touchend', '#alert .close', closeAlert)
		$('body').on('click touchend', '#popup .close', closePopup)
		$('body').on('hover', '.cell .link_wrap', hoverCell)
		$('body').on('click touchend', 'header nav .link a', toggleSeeker)
		$('body').on('click touchend', '.super.seeker .close', closeSeeker)
		
		if $popup.length
			popupObj = JSON.parse(localStorage.getItem('popup'))
			now = new Date().getTime()
			#15 minutes
			dur = 60*15*1000
			lastWeek = now - dur
			if popupObj && popupObj.shown && popupObj.time > lastWeek
				$popup.addClass('show stuck')

		if $body.is('.search')
			$('.search_header input#searchbox').focus()

		$window.on 'resize', () ->
			fixLoops()
			sizeImages()
			fixHeader()
			trackScroll()
			fixToggler()
		.resize()

		$window.on 'scroll', (e) ->
			trackScroll(e)
			fixHeader()

		setupArticle()
		animateTexts()

		watchForHover = () ->
			hasHoverClass = false
			lastTouchTime = 0

			enableHover = () ->
				if (new Date() - lastTouchTime < 500)
					return
				$body.addClass('has_hover')
				hasHoverClass = true

			disableHover = () ->
				$body.removeClass('has_hover')
				hasHoverClass = false

			updateLastTouchTime = () ->
				lastTouchTime = new Date()

			document.addEventListener('touchstart', updateLastTouchTime, true)
			document.addEventListener('touchstart', disableHover, true)
			document.addEventListener('mousemove', enableHover, true)
			enableHover()

		watchForHover()

