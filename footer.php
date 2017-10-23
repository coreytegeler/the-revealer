<?php
global $post;
global $pagename;
echo '</main>';
if( is_single() ) {
	get_template_part( 'parts/popup' );
}
echo '</div>';
if( $post->post_name != 'discover' ) {
	if( !is_404() ) {
		echo '<div id="discover" class="bottom">';
			echo '<div class="inner">';
				echo '<div class="header">';
					echo '<div class="wrap">';
						$discover_url = get_permalink( get_page_by_path( 'discover' ) );
						echo '<a href="' . $discover_url . '">';
							echo '<h2 class="glisten">';
								echo 'Discover&nbsp;&nbsp;more';
							echo '</h2>';
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
		echo '<div class="note">The Revealer is published by the <a target="_blank" href="' . $crm_url . '">Center for Religion and Media at NYU</a></div>';
		echo '<div class="note">Copyright © 2017 The Revealer All Rights Reserved.</div>';
	echo '</footer>';
}
echo '<div id="isMobile"></div>';
wp_footer();
?>
</body>
</html>