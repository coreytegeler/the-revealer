<?php
echo '<nav role="navigation" class="mobile">';
	if( has_nav_menu( 'navigation' ) ) {
		$nav_links = array_reverse( wp_get_nav_menu_items( 'navigation' ) );
		echo '<div class="links">';
			echo '<ul>';
				echo '<li>';
					echo '<div class="tagline"><em>' . get_bloginfo( 'description' ) . '</em></div>';
					echo '<div class="logo">';
						$logo_svg = get_template_directory_uri() . '/assets/images/logo.svg';
						$home_url = get_site_url();
						echo '<a class="svg" href="' . $home_url . '">';
							echo file_get_contents( $logo_svg );
						echo '</a>';
					echo '</div>';
				echo '</li>';
				foreach ( (array) $nav_links as $link ) {
					$title = $link->title;
					$slug = $link->slug;
					$url = $link->url;
					echo '<li>';
						echo '<a class="conceal" href="' . $url . '" data-slug="' . $slug . '">';
							echo $title;
						echo '</a>';
					echo '</li>';
				}
			echo '</ul>';
			if( $post->post_type == 'post' ) {
				echo '<div class="prog_bar"><div class="solid"></div></div>';
			}
		echo '</div>';
	}
echo '</nav>';
?>