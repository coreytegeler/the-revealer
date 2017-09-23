<?php
global $post;
echo '</main>';
echo '</div>';
if( $post->post_name != 'discover' ) {
	echo '<div id="discover">';
		echo '<div class="inner">';
			echo '<div class="header">';
				echo '<div class="wrap">';
					$discover_url = get_permalink( get_page_by_path( 'discover' ) );
					echo '<a href="' . $discover_url . '">';
						echo '<h1 class="glisten">';
							echo 'Discover&nbsp;more';
						echo '</h1>';
						echo '<div class="dash"></div>';
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
	echo '<footer>';
		echo '<div class="social">';
			$social = array( 'facebook', 'twitter', 'instagram' );
			foreach( $social as $name ) {
				$svg = get_template_directory_uri() . '/assets/images/' . $name . '.svg';
				echo '<a href="#" class="' . $name . '">';
					echo file_get_contents( $svg );
				echo '</a>';
			}
		echo '</div>';
		$crm_url = get_field( 'crm_url', 'option' );
		echo '<div class="note">';
			echo '<form class="newsletter">';
				echo '<label>';
					echo 'Subscribe to our monthly newsletter';
					echo '<input type="text" placeholder=""/>';
				echo '</label>';
			echo '</form>';
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