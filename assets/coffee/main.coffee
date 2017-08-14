jQuery ($) ->
	$ -> 
		$window = $(window)
		$body = $('body')
		$header = $('header')
		$footer = $('footer')
			
		track = () ->
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
			console.log winScroll
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
			# console.log bottomTop, winScroll + winHeight


			


		$window.on 'resize', track
		$window.on 'scroll', track

