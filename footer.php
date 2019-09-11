<?php
global $post;

if( is_single() ) {
	echo '<div class="transport top circle">';
		$up_svg = get_template_directory_uri() . '/assets/images/up.svg';
		echo file_get_contents( $up_svg );
	echo '</div>';
}

echo '</main>';
if( is_single() ) {
	get_template_part( 'parts/popup' );
}
echo '</div>';
if( $post->post_name != 'discover' ) {
	if( !is_404() ) {
		echo '<div id="discover" class="bottom">';
			echo '<div class="inner">';
				echo '<div class="discover_more">';
					echo '<div class="wrap">';
						$discover_url = get_permalink( get_page_by_path( 'discover' ) );
						if( $post->post_name != 'search' ) {
							echo '<a href="' . $discover_url . '">';
								echo '<h2><div class="animation glisten bounce">';
									echo wrap_words( 'discover  more' );
								echo '</div></h2>';
							echo '</a>';
						}
					echo '</div>';
				echo '</div>';
				echo '<div class="loop discover xsmall grid">';
					get_template_part( 'parts/discover' );
				echo '</div>';
				get_search_form();
			echo '</div>';
		echo '</div>';
	}
	echo '<footer id="footer">';
		$crm_url = get_field( 'crm_url', 'option' );
		echo '<div class="note newsletter">';
			get_template_part( 'parts/newsletter' );
		echo '</div>';
		echo '<div class="social">';
			$social = array( 'facebook', 'twitter', 'instagram' );
			foreach( $social as $social_name ) {
				$social_svg = get_template_directory_uri() . '/assets/images/' . $social_name . '.svg';
				$social_url = get_field( $social_name, 'options' );
				echo '<a href="' . $social_url . '" class="' . $social_name . '" target="_blank">';
					echo file_get_contents( $social_svg );
				echo '</a>';
			}
		echo '</div>';

		if( have_rows( 'footer_lines', 'option' ) ) {
		  while( have_rows( 'footer_lines', 'option' ) ) : the_row();
	      echo '<div class="note small">' . get_sub_field( 'text' ) . '</div>';
		  endwhile;
		}
	echo '</footer>';
}

if( !is_search() ) {
	echo '<div class="super seeker">';
		get_template_part( 'parts/seeker' );
		echo '<div class="close circle">';
			$x_svg = get_template_directory_uri() . '/assets/images/x.svg';
			echo file_get_contents( $x_svg );
		echo '</div>';
	echo '</div>';
}
echo '<div id="is_mobile"></div>';
$missing_url = get_template_directory_uri() . '/assets/images/missing.svg';
$missing_svg = file_get_contents( $missing_url );
echo '<div id="missingSvg" data-url="' . $missing_url . '">'.$missing_svg.'</div>';
wp_footer();
?>
<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-85617056-1', 'auto');
ga('send', 'pageview');
</script>
<!-- End Google Analytics -->
</body>
</html>