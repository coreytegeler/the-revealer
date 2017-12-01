<?php
global $post;
get_header();
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$id = $post->ID;
$date = get_the_date();
$categories = get_the_category();
$column = get_the_terms( $post, 'columns' )[0];
$column_url = add_query_arg( 'column', $column->slug, get_site_url() );
$issue = get_the_terms( $post, 'issues' )[0];
$writers = get_field( 'writers' );
$contributors = get_field( 'contributors' );
$content = $post->post_content;
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$tags = get_the_tags();
$permalink = get_the_permalink();


$content = $post->post_content;
$content = apply_filters( 'the_content', $content );
$content = str_replace( ']]>', ']]&gt;', $content );
$stripped_content = strip_tags( $content );
$excerpt = wp_strip_all_tags( get_the_excerpt() );


echo '<article class="post readable">';
	echo '<div class="text">';
		echo '<div class="lead">';
			echo '<div class="title">';
				if( $column ) {
					echo '<h2 class="label column">' . $column->name . '</h2>';
				}
				echo '<h1>';
					echo $title;
				echo '</h1>';
			echo '</div>';

			
			if( have_rows( 'writers' ) ) {
				echo '<div class="writers">';
					echo '<div class="commas">';
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
				echo '</div>';
			}

			if( have_rows( 'contributors' ) ) {
		    while( have_rows( 'contributors') ) : the_row();
	        $contributor_role = get_sub_field( 'role' );
					echo '<div class="contributors">';
						echo '<div class="value commas">';
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
					echo '</div>';
				endwhile;
			}

			echo '<div class="meta">';
				echo '<div class="date">';
					echo $date;
				echo '</div>';
			echo '</div>';
			// 	echo '<div class="row split date">';
			// 		echo '<div class="label">Published</div>';
			// 		echo '<div class="value">' . $date . '</div>';
			// 	echo '</div>';
			// 	if( $categories && $cat_length = sizeof( $categories ) ) {
			// 		echo '<div class="row split category">';
			// 			echo '<div class="label">Category</div>';
			// 			echo '<div class="value commas">';
			// 				foreach ($categories as $i => $category) {
			// 					$cat_url = get_category_link( $category->cat_ID );
			// 					$cat_name = $category->name;
			// 					echo '<span>';
			// 						if( $cat_url ) {
			// 							echo '<a href="' . $cat_url . '">' . $cat_name . '</a>';
			// 						} else {
			// 							echo $cat_name;
			// 						}
			// 					echo '</span>';
			// 				}
			// 			echo '</div>';
			// 		echo '</div>';
			// 	}
			// 	if( $column ) {
			// 		echo '<div class="row split column">';
			// 			echo '<div class="label">Column</div>';
			// 			echo '<div class="value">';
			// 				echo '<a href="' . $column_link . '">' . $column->name . '</a>';
			// 			echo '</div>';
			// 		echo '</div>';
			// 	}
			// 	if( $issue ) {
			// 		echo '<div class="row split issue">';
			// 			echo '<div class="label">Issue</div>';
			// 			echo '<div class="value">';
			// 				echo $issue->name;
			// 			echo '</div>';
			// 		echo '</div>';
			// 	}

			// 	echo '<div class="row split share">';
			// 		echo '<div class="label">Share</div>';
			// 		echo '<div class="value commas">';
			// 			$facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink;
			// 			$twitter = 'https://twitter.com/share?url=https://www.democracynow.org/2017/10/24/expansion_of_imperialist_us_war_on&text=' . urlencode( $title . ' ' . $permalink );
			// 			$email = 'mailto:?body=' . urlencode( $permalink );
			// 			echo '<span><a class="window" href="' . $facebook . '">Facebook</a></span>';
			// 			echo '<span><a class="window" href="' . $twitter . '">Twitter</a></span>';
			// 			echo '<span><a href="' . $email . '">Email</a></span>';
			// 		echo '</div>';
			// 	echo '</div>';
			// 	echo '<div class="row images split hide">';
			// 		echo '<div class="label">Images</div>';
			// 		echo '<div class="value">';
			// 			echo '<div class="loop masonry xsmall"></div>';
			// 		echo '</div>';
			// 	echo '</div>';
			// 	if( $tags ) {
			// 		echo '<div class="row tags commas listWrap">';
			// 			echo '<div class="list">';
			// 				echo '<span class="label">Tags</span>';
			// 				foreach ( $tags as $tag ) {
			// 					$tag_name = $tag->name;
			// 					$tag_url = get_tag_link( $tag->term_id );
			// 					echo '<span class="tag">';
			// 						echo '<a href="' . $tag_url . '">' . $tag_name . '</a>';
			// 					echo '</span>';
			// 				}
			// 			echo '</div>';						
			// 			echo '<div class="toggle">';
			// 				echo '<div class="circle">';
			// 					$up_svg = get_template_directory_uri() . '/assets/images/up.svg';
			// 					echo file_get_contents( $up_svg );
			// 				echo '</div>';
			// 			echo '</div>';
			// 		echo '</div>';
			// 	}
			// echo '</div>';

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