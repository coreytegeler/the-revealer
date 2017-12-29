<?php
global $post;
global $pagename;

echo '<div class="transport top circle">';
	$up_svg = get_template_directory_uri() . '/assets/images/up.svg';
	echo file_get_contents( $up_svg );
echo '</div>';


echo '</main>';
if( is_single() ) {
	get_template_part( 'parts/popup' );
}
echo '</div>';
if( $post->post_name != 'discover' ) {
	if( !is_404() && $post->post_name != 'search' ) {
		echo '<div id="discover" class="bottom">';
			echo '<div class="inner">';
				echo '<div class="header">';
					echo '<div class="wrap">';
						$discover_url = get_permalink( get_page_by_path( 'discover' ) );
						echo '<a href="' . $discover_url . '">';
							echo '<h3 class="glisten bounce">';
								echo 'discover&nbsp;more';
							echo '</h3>';
						echo '</a>';
					echo '</div>';
					// echo '<div class="circle"></div>';
				echo '</div>';
				echo '<div class="loop discover xsmall grid">';
					get_template_part( 'parts/discover' );
				echo '</div>';
				get_search_form();
			echo '</div>';
		echo '</div>';
	}
	echo '<footer>';
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

		if( have_rows('footer_lines', 'option') ) {
		  while( have_rows('footer_lines', 'option') ) : the_row();
	      echo '<div class="note">' . get_sub_field('text') . '</div>';
		  endwhile;
		}
	echo '</footer>';
}
echo '<div id="isMobile"></div>';
$missing_url = get_template_directory_uri() . '/assets/images/missing.svg';
$missing_svg = file_get_contents( $missing_url );
echo '<div id="missingSvg" data-url="' . $missing_url . '">'.$missing_svg.'</div>';
wp_footer();
?>
</body>
</html>