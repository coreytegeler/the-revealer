<?php
get_header();
$missing_url = get_template_directory_uri() . '/assets/images/question.svg';
$missing_svg = file_get_contents( $missing_url );
$con_args = array(
	'post_type' => 'contributor',
	'posts_per_page' => -1
);
echo '<div class="readable">';
	echo '<div class="max">';
		echo '<ul class="nested parent contributors">';
			echo '<li>';
				echo '<h2>Contributors</h2>';
			echo '</li>';
			$con_query = new WP_Query( $con_args );
			if( $con_query->have_posts() ) { 
		    while( $con_query->have_posts()) { 
			    $con_query->the_post();
					$contributor = $post;
					$name = $contributor->post_title;
					$slug = $contributor->post_name;
					$id = $contributor->ID;
					$permalink = get_permalink( $id );
					$content = $post->post_content;
					// $count = $contributor->contributor_count;
					echo '<li class="contributor">';
						echo '<div class="child">';
							echo '<span class="title"><em>' . $name . '</em></span> ';
							echo '<span class="body">' . $content . '</span>';
						
							echo '<div class="loop posts xxsmall grid">';
								$posts_args = array(
									'post_type' => 'post',
									'posts_per_page' => 3,
									'tag' => $slug,
								);
								query_posts( $posts_args );
								if ( have_posts() ) {
									while ( have_posts() ) {
										the_post();
										$title = $post->post_title;
										$thumb_id = get_post_thumbnail_id();
										$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
										$thumb_url = $thumb[0];
										$thumb_width = $thumb[1];
										$thumb_height = $thumb[2];
										$permalink = get_permalink();
										echo '<div class="cell">';
											echo '<a href="' . $permalink . '" class="link_wrap">';
												echo '<div class="' . ( $thumb ? 'image load' : 'missing' ) . '">';
													if ( $thumb ) {
														echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'" alt="' . $title . '"/>';
													} else {
														echo '<object data="' . $missing_url . '" alt="' . $title . '" type="image/svg+xml">' . $title . '</object>';
													}
												echo '</div>';
											echo '</a>';
										echo '</div>';
									}
								}
								wp_reset_postdata();
							echo '</div>';
							echo '<div class="links">';
								echo '<a href="' . $permalink . '">View all posts</a>';
							echo '</div>';
						echo '</div>';
					echo '</li>';
				}
			}
			wp_reset_postdata();
		echo '</ul>';
	echo '</div>';
echo '</div>';
get_footer();
?>