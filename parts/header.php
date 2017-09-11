<?php
// global $post;
echo '<nav role="navigation" class="main">';
	echo '<div id="logo">';
		$logo_svg = get_template_directory_uri() . '/assets/images/logo.svg';
		$home_url = get_site_url();
		echo '<a class="svg bounce" href="' . $home_url . '">';
			echo file_get_contents( $logo_svg );
			$about = get_page_by_path( 'about' )->post_content;
		echo '</a>';
		// echo '<span class="tagline">';
		// 	echo '&nbsp;(' . get_bloginfo( 'description' ) . ')';
		// 	if( $post->post_name == 'about' ) {
		// 		echo '<span class="about">&nbsp;' . $about . '</span>';
		// 	}
		// echo '</span>';
	echo '</div>';
	echo '<div class="inner">';
		if( has_nav_menu( 'navigation' ) ) {
			$nav_links = array_reverse( wp_get_nav_menu_items( 'navigation' ) );
			echo '<div class="row navigation">';
				echo '<div class="links">';
					foreach ( (array) $nav_links as $link ) {
						$title = $link->title;
						$slug = $link->slug;
						$url = $link->url;
						echo '<div class="link">';
							echo '<a class="conceal" href="' . $url . '" data-slug="' . $slug . '">';
								echo $title;
							echo '</a>';
						echo '</div>';
					}
				echo '</div>';
			echo '</div>';
		}
		// echo '<div class="row filters">';
		// 	get_template_part( 'parts/filters' );
		// echo '</div>';
	echo '</div>';
	if( $post->post_type == 'post' ) {
		echo '<div class="prog_bar"><div class="solid"></div></div>';
	}
echo '</nav>';
	// if( is_archive() ) {
	// 	$categories = get_categories( array(
	//     'orderby' => 'name',
	//     'order' => 'ASC',
	// 		'exclude' => 1,
	// 		'parent' => 0,
	// 		'hide_empty' => true
	// 	) );
	// echo '<ul class="sub row">';
	// 	foreach ( (array) $categories as $key => $cat ) {
	// 		echo '<li>';
	// 			$url = get_category_link( $cat->term_id );
	// 			echo '<a class="conceal" href="' . $url . '">' . $cat->name . '</a>';
	// 		echo '</li>';
	// 	}
	// echo '</ul>';
	// }

?>