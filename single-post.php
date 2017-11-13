<?php
global $post;
get_header();
echo '<article class="post readable">';
	$content = $post->post_content;
	$content = apply_filters( 'the_content', $content );
	$content = str_replace( ']]>', ']]&gt;', $content );
	$stripped_content = strip_tags( $content );
	$excerpt = wp_strip_all_tags( get_the_excerpt() );
	$title = $post->post_title;
	$column = get_the_terms( $post, 'columns' )[0];
	echo '<div class="text">';
		echo '<div class="excerpt max">';
			echo '<h2>' . $excerpt . '</h2>';
		echo '</div>';
		echo '<div class="content">';
			echo $content;
		echo '</div>';
	echo '</div>';
	get_template_part( 'parts/pagination' );
echo '</article>';

related_posts();

echo '<div class="carousel" id="carousel">';
	echo '<div class="slides">';
	echo '</div>';
	echo '<div class="arrow left" data-direction="left"></div>';
	echo '<div class="arrow right" data-direction="right"></div>';
	echo '<div class="close_padding">';
		echo '<div class="close circle">';
			$x_svg = get_template_directory_uri() . '/assets/images/x.svg';
			echo file_get_contents( $x_svg );
		echo '</div>';
	echo '</div>';
echo '</div>';
echo '<div class="transport top circle">';
	$up_svg = get_template_directory_uri() . '/assets/images/up.svg';
	echo file_get_contents( $up_svg );
echo '</div>';
get_footer();
?>