<?php
global $post;
get_header();
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$id = $post->ID;
$date = get_the_date();
$issue = get_field( 'issue' );
$categories = get_cat_list( $id, true );
$column = get_the_terms( $post, 'columns' )[0];
$column_url = add_query_arg( 'column', $column->slug, get_site_url() );
$issue = get_the_terms( $post, 'issues' )[0];
$writers = get_field( 'writers' );
$contributors = get_contributors_list( $id, true, true );
// $contributors = get_field( 'contributors' );
$content = $post->post_content;
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$tags = get_the_tags();
$permalink = get_the_permalink();

$articles_url = get_articles_page();

$content = $post->post_content;
$content = apply_filters( 'the_content', $content );
$content = str_replace( ']]>', ']]&gt;', $content );
$stripped_content = strip_tags( $content );
$excerpt = wp_strip_all_tags( get_the_excerpt() );

foreach( get_the_category( $id ) as $i => $cat ) {
	$category_class .= $cat->slug.' ';
}
echo '<article class="post readable ' . $category_class . '">';
	echo '<div class="text">';
		echo '<div class="lead">';
			echo '<div class="header">';
				if( $column ) {
					echo '<h1 class="label column">' . $column->name . '</h1>';
				}
				echo '<h1 class="title">';
					echo $title;
				echo '</h1>';
			echo '</div>';

			echo '<div class="meta">';
				if( have_rows( 'writers' ) ) {
					echo '<div class="row writers">';
						echo '<span>by </span>';
				    while( have_rows( 'writers') ) : the_row();
							$writer_name = get_sub_field( 'name' );
							$writer_url = get_sub_field( 'url' );
							echo '<span>';
								if( $writer_url ) {
									echo '<a href="' . urlify( $writer_url ) . '">' . $writer_name . '</a>';
								} else {
									echo $writer_name;
								}
							echo '</span>';
						endwhile;
					echo '</div>';
				}

				if( have_rows( 'contributors' ) ) {
			    while( have_rows( 'contributors') ) : the_row();
		        $contributor_role = get_sub_field( 'role' );
						echo '<div class="row contributors">';
							$contributor_name = get_sub_field( 'name' );
							$contributor_url = get_sub_field( 'url' );
							echo '<span>';
								echo $contributor_role . ' ';
								if( $contributor_url ) {
									echo '<a href="' . urlify( $contributor_url ) . '">' . $contributor_name . '</a>';
								} else {
									echo $contributor_name;
								}
							echo '</span>';
						echo '</div>';
					endwhile;
				}
				echo '<div class="row date">';
					echo 'Published on ' . $date;
				echo '</div>';
			echo '</div>';

			echo '<div class="excerpt max">';
				echo '<h2>' . $excerpt . '</h2>';
			echo '</div>';
		echo '</div>';

		if( is_archived() && $archive_alert = get_field( 'archive_alert', 'option' ) ) {
			echo '<div id="alert" role="alert">';
				echo '<div class="message">';
					echo  '<div class="archive_alert">' . $archive_alert . '</div>';
				echo '</div>';
				// echo '<div class="close">';
				// 	$x_svg_url = get_template_directory_uri() . '/assets/images/x.svg';
				// 	$x_svg = file_get_contents( $x_svg_url );
				// 	echo '<div class="circle">' . $x_svg . '</div>';
				// echo '</div>';
			echo '</div>';
		}
		
		echo '<div class="content">';
			echo $content;
		echo '</div>';

		echo '<div class="foot">';
			echo '<div class="meta">';
				if( $issue && $issue_name = $issue->name ) {
					echo '<div class="row issue commas">';
						echo '<span class="no_comma">Issue:&nbsp;</span>';
						echo '<span><a href="#">' . $issue_name . '</a></span>';
					echo '</div>';
				}

				if( $categories ) {
					echo '<div class="row categories commas">';
						if( sizeof( get_the_category( $id ) ) > 1 ) {
							echo '<span class="no_comma">Categories:&nbsp;</span>';
						} else {
							echo '<span class="no_comma">Category:&nbsp;</span>';
						}
						echo $categories;
					echo '</div>';
				}

				if( $tags ) {
					echo '<div class="row tags commas">';
						echo '<span class="no_comma">Tags:&nbsp;</span>';
						foreach( $tags as $tag ) {
							$tag_url = add_query_arg( 'tag', $tag->slug, $articles_url );
							echo '<span>';
								echo '<a href="' . $tag_url . '" class="tag">' . $tag->name . '</a>';
							echo '</span>';
						}
					echo '</div>';
				}

				echo '<div class="social share">';
					echo '<div class="rows links">';
						echo '<span>Share this article on </span>';
						$fb_svg = get_template_directory_uri() . '/assets/images/facebook.svg';
						$twitter_svg = get_template_directory_uri() . '/assets/images/twitter.svg';
						echo '<a href="https://www.facebook.com/sharer/sharer.php?sdk=joey&u=' . $permalink . '">' . file_get_contents( $fb_svg ) . '</a>';
						echo '<span> or </span>';
						echo '<a href="https://twitter.com/share?url=' . $permalink . '">' . file_get_contents( $twitter_svg ) . '</a>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div>';

	echo '</div>';
	// get_template_part( 'parts/pagination' );
echo '</article>';

related_posts();

echo '<div class="carousel" id="carousel">';
	echo '<div class="slides">';
	echo '</div>';
	$left_svg = get_template_directory_uri() . '/assets/images/left.svg';
	$right_svg = get_template_directory_uri() . '/assets/images/right.svg';
	echo '<div class="arrow left" data-direction="left">' . file_get_contents( $left_svg ) . '</div>';
	echo '<div class="arrow right" data-direction="right">' . file_get_contents( $right_svg ) . '</div>';
	echo '<div class="close circle">';
		$x_svg = get_template_directory_uri() . '/assets/images/x.svg';
		echo file_get_contents( $x_svg );
	echo '</div>';
echo '</div>';
get_footer();
?>