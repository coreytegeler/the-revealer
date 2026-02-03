<?php
global $post;
$donate_url = get_field( 'donate_url', 'options' );
echo '<header class="main" id="header">';
	echo '<div class="inner">';
		echo '<div id="logo">';
			$home_url = get_site_url();
			echo '<a class="svg" href="' . $home_url . '">';
				echo get_svg( 'logo' );
				$about = get_page_by_path( 'about' )->post_content;
			echo '</a>';
		echo '</div>';
		echo '<div class="toggle" data-toggle="nav">';
			echo '<div class="circle">';
				echo get_svg( 'down' );
			echo '</div>';
		echo '</div>';
		echo '<div class="rows">';
			echo '<div class="row tagline">';
				echo get_bloginfo( 'description' );
			echo '</div>';
			echo '<div class="row navigation toggler" data-toggle="nav">';
				if( has_nav_menu( 'navigation' ) ) {
					$nav_menu_items = wp_get_nav_menu_items( 'navigation' );
					echo '<nav role="navigation">';
						foreach( $nav_menu_items as $link ) {
							$title = $link->title;
							$url = $link->url;
							echo '<div class="link">';
								echo '<a href="' . $url . '" data-title="' . $title . '">';
									echo $title;
								echo '</a>';
							echo '</div>';
						}
						echo '<div class="link">';
							echo '<a href="' . $donate_url . '" target="_blank" data-title="Donate" class="donate">';
								echo 'Donate';
							echo '</a>';
						echo '</div>';
					echo '</nav>';

					echo '<nav role="navigation" class="mobile_nav intra">';
						foreach( $nav_menu_items as $link ) {
							$title = $link->title;
							$url = $link->url;
							echo '<div class="link">';
								echo '<a href="' . $url . '" data-title="' . $title . '">';
									echo $title;
								echo '</a>';
							echo '</div>';
						}
						echo '<div class="link">';
							echo '<a href="' . $donate_url . '" target="_blank" data-title="Donate">';
								echo 'Donate';
							echo '</a>';
						echo '</div>';
					echo '</nav>';
					// get_template_part( 'parts/goldbar' );
				}
			echo '</div>';
		echo '</div>';
		get_template_part( 'parts/goldbar' );
	echo '</div>';
echo '</header>';
?>