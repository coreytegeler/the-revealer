<?php
get_header();
global $post;
$home_url = get_site_url();
$issues = get_terms( array(
  'taxonomy' => 'issues',
  'hide_empty' => false,
  'orderby' => 'date',
  'order' => 'desc'
) );
$current_issue = $issues[0];
$current_issue_id = $current_issue->term_id;

$current_issue_args = array(
	'post_type' => 'post',
	'orderby' => 'date',
  'order' => 'asc',
  'tax_query' => array(
  	array(
			'taxonomy' => 'issues',
			'field' => 'id',
			'terms' => $current_issue_id
		)
  )
);

$already_used = array();

$features_amount = 1;
$medium_amount = 15;
$tagged_amount = 4;

$articles_page = get_page_by_path( 'articles' );
if( $articles_page ) {
	$page_url = get_permalink( $articles_page );
} else {
	$page_url = get_site_url() . '/articles/';
}

// print_r( $current_issue );
echo '<div class="readable">';
	// echo '<div class="sections one_one">';
	// 	echo '<section id="cover">';
			
	// 	echo '</section>';
	// 	echo '<section>';
	// 		$features_args = array_merge( $current_issue_args, array(
	// 			'posts_per_page' => $features_amount,
	// 			'category_name' => 'features'
	// 		) );
	// 		$features_query = new WP_Query( $features_args );
	// 		if ( $features_query->have_posts() ) {
	// 			echo '<div class="loop articles one_col masonry">';
	// 				while ( $features_query->have_posts() ) {
	// 					$features_query->the_post();
	// 					get_template_part( 'parts/article' );
	// 					$already_used[] = get_the_ID();
	// 				}
	// 			echo '</div>';
	// 		}
	// 		wp_reset_query();
	// 	echo '</section>';	
	// echo '</div>';

	echo '<div class="loop articles two_col masonry">';
		echo '<div class="cell" id="cover">';
			echo '<div class="overlay">';
				echo '<h1 class="lead">Read our current issue</h1>';
				echo '<h1 class="title">' . $current_issue->name  . '</h1>';
				$issue_date = get_field( 'date', $current_issue );
				echo '<h1 class="date">published on ' . $issue_date . '</h1>';
			echo '</div>';
			// echo '<div class="circle"></div>';
			// $current_issue_query = new WP_Query( 
			// 	array_merge( $current_issue_args, array(
			// 		'posts_per_page' => -1
			// 	) )
			// );
			// if ( $current_issue_query->have_posts() ) {
			// 	echo '<div class="loop masonry xxsmall">';
			// 		while ( $current_issue_query->have_posts() ) {
			// 			$current_issue_query->the_post();
			// 			$article_id = get_the_ID();
			// 			$article_permalink = get_permalink();
			// 			$thumb_id = get_post_thumbnail_id();
			// 			$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
			// 			$thumb_url = $thumb[0];
			// 			$thumb_width = $thumb[1];
			// 			$thumb_height = $thumb[2];
			// 			echo '<article class="cell" role="article" style="' . $style . '" data-id="' . $article_id . '">';
			// 				echo '<div class="wrap">';
			// 					echo '<div class="primary">';
			// 						echo '<a class="link_wrap" href="' . $article_permalink . '">';
			// 							if ( $thumb ) {
			// 								echo '<div class="image load">';
			// 									echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
			// 								echo '</div>';
			// 							}
			// 						echo '</a>';
			// 					echo '</div>';
			// 				echo '</div>';
			// 			echo '</article>';
			// 		}
			// 	echo '</div>';
			// }
			// wp_reset_query();
		echo '</div>';

		echo '<div class="cell" id="newsletter">';
			echo '<div class="newsletter">';
				get_template_part( 'parts/newsletter' );
			echo '</div>';
		echo '</div>';

		$features_args = array_merge( $current_issue_args, array(
			'posts_per_page' => $features_amount,
			'category_name' => 'features'
		) );
		$features_query = new WP_Query( $features_args );
		if ( $features_query->have_posts() ) {
			while ( $features_query->have_posts() ) {
				$features_query->the_post();
				get_template_part( 'parts/article' );
				$already_used[] = get_the_ID();
			}
		}
		wp_reset_query();



		$current_issue_args = array_merge( $current_issue_args, array(
			'posts_per_page' => 15,
			'post__not_in' => $already_used
		) );
		$current_issue_query = new WP_Query( $current_issue_args );
		if ( $current_issue_query->have_posts() ) {
			while ( $current_issue_query->have_posts() ) {
				$current_issue_query->the_post();
				get_template_part( 'parts/article' );
				$already_used[] = get_the_ID();
			}
		}
		wp_reset_query();
	echo '</div>';

	////////////////////////////////////////////////////////////////
	// echo '</div></main>';
	// echo '<main>';
	// echo '<div class="readable">';
	////////////////////////////////////////////////////////////////
	echo '<div class="goldbar"><div class="solid"></div></div>';

	echo '<div class="sections one_one">';

		echo '<section>';
			$col_args = array(
				'post_type' => 'post',
				'orderby' => 'date',
			  'order' => 'asc',
				'posts_per_page' => 1,
				'post__not_in' => $already_used,
				'tax_query' => array(
					array(
						'taxonomy' => 'columns',
						'operator' => 'EXISTS'
					)
				)
			);
			$col_query = new WP_Query( $col_args );
			if ( $col_query->have_posts() ) {
				$feat_col = get_the_terms( $col_query->posts[0], 'columns' )[0];
				$feat_col_name = $feat_col->name;
				$feat_col_slug = $feat_col->slug;
				$feat_col_writer = get_field( 'writer', $feat_col );
				$feat_col_url = add_query_arg( 'column', $feat_col_slug, $page_url );

				echo '<h2 class="section_header">Another recent article from, ';
					echo '<a href="' . $col_url . '">';
							echo '<em>' . $feat_col_name . '</em>';
						echo '</a>';
					echo ', a column by ';
					$feat_col_url = $home_url . '/?s=' . urlencode( $feat_col_writer );
					echo '<a href="' . $feat_col_url . '">';
						echo '<em>' . $feat_col_writer . '</em>';
					echo '</a>';
				echo '.</h2>';
				echo '<div class="loop articles one_col masonry" id="recent_column">';
					while ( $col_query->have_posts() ) {
						$col_query->the_post();
						get_template_part( 'parts/article' );
						$already_used[] = get_the_ID();
					}
				echo '</div>';
			}
			wp_reset_query();


			$columns_page = get_page_by_path( 'columns' );
			if( $columns_page ) {
				$columns_url = get_permalink( $columns_page );
			} else {
				$columns_url = get_site_url() . '/columns/';
			}
			echo '<h2 class="section_header">More ';
				echo '<a href="' . $columns_url . '"><em>columns</em></a>';
			echo ' from The Revealer</h2>';
			$columns = get_terms( array(
				'taxonomy' => 'columns',
			  'orderby' => 'name',
			  'order'   => 'ASC'
			) );
			if( sizeof( $columns ) ) {
				echo '<div class="columns">';
					foreach( $columns as $col) {
						$col_title = $col->name;
						$col_slug = $col->slug;
						$col_id = $col->cat_ID;
						$col_writer = get_field( 'writer', $col );
						$col_url = add_query_arg( 'column', $col_slug, $page_url );
						echo '<div class="column">';
							echo '<a href="' . $col_url . '" class="column">';
								echo '<h1>' . $col_title . '<span>&mdash;' . $col_writer . '</span></h1>';
							echo '</a>';
						echo '</div>';
					}
				echo '</div>';
			}
		echo '</section>';

		echo '<section>';

			$feat_tags = get_field( 'featured_tags', $current_issue );
			$feat_tag_i = array_rand( $feat_tags, 1 );
			$feat_tag = $feat_tags[$feat_tag_i];
			echo '<h2 class="section_header">Some more articles about ';
				echo '<a href="' . $feat_tag->slug . '">';
					echo '<em>' . $feat_tag->name . '</em>.';
				echo '</a>';
			echo '</h2>';
			echo '<div class="loop articles masonry" id="featured_tag">';
				$tagged_args = array(
					'posts_per_page' => $tagged_amount,
					'post_type' => 'post',
					'post__not_in' => $already_used,
				  'tag' => $feat_tag->slug
				);
				$tagged_query = new WP_Query( $tagged_args );
				if ( $tagged_query->have_posts() ) {
					while ( $tagged_query->have_posts() ) {
						$tagged_query->the_post();
						get_template_part( 'parts/article' );
						$already_used[] = get_the_ID();
					}
				}
				wp_reset_query();
			echo '</div>';
		echo '</section>';

	echo '</div>';

	$past_issues = get_terms( array(
	  'taxonomy' => 'issues',
	  'hide_empty' => false,
	  'orderby' => 'date',
	  'order' => 'desc'
	) );
	$past_issue = $past_issues[1];
	$past_issue_id = $past_issue->term_id;
	$past_issue_args = array(
		'post_type' => 'post',
		'orderby' => 'date',
	  'order' => 'asc',
	  'tax_query' => array(
	  	array(
				'taxonomy' => 'issues',
				'field' => 'id',
				'terms' => $past_issue_id
			)
	  )
	);
	$past_issue_args = array_merge( $past_issue_args, array(
		'post__not_in' => $already_used
	) );
	$past_issue_query = new WP_Query( $past_issue_args );
	if ( $past_issue_query->have_posts() ) {
		echo '<section>';
			echo '<h2 class="section_header">Catch up on our last issue</h2>';
			echo '<div class="loop articles five_col grid" id="past_issue">';
				while ( $past_issue_query->have_posts() ) {
					$past_issue_query->the_post();
					$title = $post->post_title;
					$thumb_id = get_post_thumbnail_id();
					$thumb = wp_get_attachment_image_src( $thumb_id, 'thumb' );
					$thumb_url = $thumb[0];
					$thumb_width = $thumb[1];
					$thumb_height = $thumb[2];
					$permalink = get_the_permalink();
					echo '<article class="cell" role="article" style="' . $style . '">';
						echo '<div class="wrap">';
							echo '<div class="primary">';
								echo '<a class="link_wrap" href="' . $permalink . '">';
									echo '<div class="' . ( $thumb ? 'image load' : 'missing') . '">';
										if ( $thumb ) {
											echo '<img data-src="'.$thumb_url.'" data-width="'.$thumb_width.'" data-height="'.$thumb_height.'"/>';
										}
									echo '</div>';
								echo '</a>';
							echo '</div>';
							echo '<div class="secondary">';
								echo '<a class="link_wrap" href="' . $permalink . '">';
									echo '<div class="title">';
										echo '<h4>' . $title . '</h4>';
									echo '</div>';
								echo '</a>';
							echo '</div>';
						echo '</div>';
					echo '</article>';
					$already_used[] = get_the_ID();
				}
			echo '</div>';
		echo '</section>';
	}
	wp_reset_query();

	get_template_part( 'parts/pagination' );

echo '</div>';
get_footer();
?>