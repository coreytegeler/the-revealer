<?php
global $post;
$title = $post->post_title;
$thumb = get_the_post_thumbnail_url( $post );
$slug = $post->post_name;
$id = $post->ID;
$date = get_the_date();
$categories = get_the_category();
$column = get_the_terms( $post, 'columns' )[0];
$column_url = add_query_arg( 'column', $column->slug, get_site_url() );
$writers = get_field( 'writers' );
$contributors = get_field( 'contributors' );
$content = $post->post_content;
$excerpt = wp_strip_all_tags( get_the_excerpt() );
$tags = get_the_tags();
$permalink = get_the_permalink();
echo '<div id="about" role="contentinfo">';
	echo '<div class="max">';
		echo '<div class="info">';
			echo '<div class="title">';
				if( $column ) {
					echo '<h2 class="label column">' . $column->name . '</h2>';
				}
				echo '<h1>';
					echo $title;
				echo '</h1>';
			echo '</div>';
			echo '<div class="meta">';
				if( have_rows('writers' ) ) {
			    while( have_rows('writers') ) : the_row();
		        $name = get_sub_field( 'name' );
						echo '<div class="row split author">';
							echo '<div class="label">Author</div>';
							echo '<div class="value commas">';
								foreach ( $writers as $writer ) {
									$writer_name = get_sub_field( 'name' );
									$writer_url = get_sub_field( 'url' );
									echo '<span>';
										if( $writer_url ) {
											echo '<a href="' . $writer_url . '">' . $writer_name . '</a>';
										} else {
											echo $writer_name;
										}
									echo '</span>';
								}
							echo '</div>';
						echo '</div>';
					endwhile;
				}
				echo '<div class="row split date">';
					echo '<div class="label">Published</div>';
					echo '<div class="value">' . $date . '</div>';
				echo '</div>';
				if( $categories && $cat_length = sizeof( $categories ) ) {
					echo '<div class="row split category">';
						echo '<div class="label">Category</div>';
						echo '<div class="value commas">';
							foreach ($categories as $i => $category) {
								$cat_url = get_category_link( $category->cat_ID );
								echo '<span><a href="' . $cat_url . '">' . $category->name . '</a><span>';
							}
						echo '</div>';
					echo '</div>';
				}
				if( $column ) {
					echo '<div class="row split column">';
						echo '<div class="label">Column</div>';
						echo '<div class="value">';
							echo '<a href="' . $column_link . '">' . $column->name . '</a>';
						echo '</div>';
					echo '</div>';
				}
				echo '<div class="row split share">';
					echo '<div class="label">Share</div>';
					echo '<div class="value commas">';
						$facebook = 'https://www.facebook.com/sharer/sharer.php?u=' . $permalink;
						$twitter = 'https://twitter.com/share?url=https://www.democracynow.org/2017/10/24/expansion_of_imperialist_us_war_on&text=' . urlencode( $title . ' ' . $permalink );
						$email = 'mailto:?body=' . urlencode( $permalink );
						echo '<span><a class="window" href="' . $facebook . '">Facebook</a></span>';
						echo '<span><a class="window" href="' . $twitter . '">Twitter</a></span>';
						echo '<span><a href="' . $email . '">Email</a></span>';
					echo '</div>';
				echo '</div>';
				echo '<div class="row images split hide">';
					echo '<div class="label">Images</div>';
					echo '<div class="value">';
						echo '<div class="loop masonry xsmall"></div>';
					echo '</div>';
				echo '</div>';
				if( $tags ) {
					echo '<div class="row tags commas">';
						echo '<span class="label">Tags</span>';
						foreach ( $tags as $tag ) {
							$tag_name = $tag->name;
							$tag_url = get_tag_link( $tag->term_id );
							echo '<span class="tag">';
								echo '<a href="' . $tag_url . '">' . $tag_name . '</a>';
							echo '</span>';
						}
					echo '</div>';
				}
			echo '</div>';
		echo '</div>';
	echo '</div>';
echo '</header>';
?>