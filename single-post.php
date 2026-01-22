<?php
global $post;
get_header();
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$id = $post->ID;
$date = get_the_date();
$categories = get_cat_list( $id, true );
$columns = get_the_terms( $post, 'columns' );
$issues = get_the_terms( $post, 'issues' );
$tags = get_the_tags();
$permalink = get_the_permalink();

$articles_url = get_articles_page();

$content = apply_filters( 'the_content', $post->post_content );
$content = str_replace( ']]>', ']]&gt;', $content );
$stripped_content = strip_tags( $content );
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$category_class = '';
foreach( get_the_category( $id ) as $i => $cat ) {
	$category_class .= $cat->slug.' ';
}
echo '<article class="post readable ' . $category_class . '">';
	echo '<div class="text">';
		echo '<div class="lead">';
			echo '<div class="header">';
				if( $columns ) {
					echo '<h1 class="label column">' . $columns[0]->name . '</h1>';
				}
				echo '<h1 class="title">';
					echo $title;
				echo '</h1>';
			echo '</div>';

			echo '<div class="meta">';
				if( have_rows( 'writers' ) ) {
					echo '<div class="row writers">';
						echo 'by&nbsp;';
				    while( have_rows( 'writers') ) : the_row();
							$writer_name = get_sub_field( 'name' );
							$writer_url = get_sub_field( 'url' );
							echo '<span class="writer">';
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
							echo '<span class="contributor">';
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
				// 	$x_svg = get_svg( $x_svg_url );
				// 	echo '<div class="circle">' . $x_svg . '</div>';
				// echo '</div>';
			echo '</div>';
		}
		
		echo '<div class="content">';
			echo wpautop( $content );
		echo '</div>';

		echo '<div class="foot">';
			echo '<div class="meta">';
				if( $issues ) {
					$issue_name = $issues[0]->name;
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
						echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $permalink ) . '" title="Facebook" target="_blank">' . get_svg( 'facebook' ) . '</a>';
						echo '<span> or </span>';
						// echo '<a href="https://twitter.com/share?url=' . urlencode( $permalink ) . '" target="_blank">' . get_svg( 'twitter' ) . '</a>';
						echo '<a href="https://bsky.app/intent/compose?text=' . urlencode( $permalink ) . '" title="Bluesky" target="_blank">' . get_svg( 'bluesky' ) . '</a>';
					echo '</div>';
				echo '</div>';

				echo '<div class="donate share">';
					echo '<p>';
						echo 'Support The Revealer’s work with a <a href="https://www.givecampus.com/campaigns/25897/donations/new?designation=centerforreligionandmediafund&" target="_blank">tax-deductible gift</a> to the Center for Religion and Media at NYU';
					echo '</p>';
				echo '</div>';


			echo '</div>';
		echo '</div>';

	echo '</div>';
	// get_template_part( 'parts/pagination' );
echo '</article>';

yarpp_related();

echo '<div class="carousel" id="carousel">';
	echo '<div class="slides"></div>';
	echo '<div class="arrow left" data-direction="left">' . get_svg( 'left' ) . '</div>';
	echo '<div class="arrow right" data-direction="right">' . get_svg( 'right' ) . '</div>';
	echo '<div class="close circle">';
		echo get_svg( 'x' );
	echo '</div>';
echo '</div>';
get_footer();
?>