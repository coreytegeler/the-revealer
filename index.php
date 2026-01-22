<?php
get_header();
global $post;
$home = get_page_by_path( 'home' );
$home_url = get_site_url();

$issues = get_terms( array(
	'taxonomy' => 'issues',
	'hide_empty' => false,
	'order' => 'desc',
	'orderby' => 'meta_value',
	'meta_key' => 'date',
	'posts_per_page' => 2
) );

$current_issue = $issues[0];
$current_issue_id = $current_issue->term_id;
$current_issue_url = get_term_link( $current_issue_id, 'issues' );

$current_issue_args = array(
	'post_type' => 'post',
	'orderby' => 'date',
	'order' => 'asc',
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'issues',
			'field' => 'id',
			'terms' => $current_issue_id
		)
	)
);

$current_issue_query = new WP_Query( $current_issue_args );
$current_issue_posts = $current_issue_query->posts;
$current_issue_date = get_field( 'date', $current_issue );

$already_used = array();

$articles_page = get_page_by_path( 'articles' );
if( $articles_page ) {
	$articles_url = get_permalink( $articles_page );
} else {
	$articles_url = get_site_url() . '/articles/';
}

echo '<div class="readable">';
	echo '<h1 class="sr-only">The Revealer</h1>';
	echo '<div class="loop row articles" id="home_top">';
		echo '<div class="col col-12 col-md-6 cover">';
			echo '<div class="inner issue">';
				echo '<div class="text">';
					echo '<div class="lead">Read our current issue</div>';
					echo '<div class="title">';
						echo '<a href="' . $current_issue_url . '">' . $current_issue->name . '</a>';
					echo '</div>';	
					echo '<h2 class="date">published ' . $current_issue_date . '</h2>';
				echo '</div>';
			echo '</div>';
		echo '</div>';

		echo '<div class="col col-12 col-md-6 newsletter">';
			echo '<div class="inner text">';
				get_template_part( 'parts/newsletter' );
			echo '</div>';
		echo '</div>';

	echo '</div>';

	echo '<div class="loop articles row" id="current_issue">';
		foreach( $current_issue_posts as $post ) {
			set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-4' );
			set_query_var( 'article', $post );
			get_template_part( 'parts/article' );
			$already_used[] = $post->ID;
		}
		wp_reset_query();
	echo '</div>';
	get_template_part( 'parts/goldbar' );

	echo '<div class="sections row">';

		if( $feat_tags = get_field( 'featured_tags', $current_issue ) ) {
			echo '<div class="col col-12 col-lg-6 col-xl-8">';
				echo '<section id="featured_tags">';
					$feat_tag_i = array_rand( $feat_tags, 1 );
					$feat_tag = $feat_tags[$feat_tag_i];
					$feat_tag_url = add_query_arg( 'tag', $feat_tag->slug, $articles_url );
					$feat_tag_link = '<a href="' . $feat_tag_url . '"><em>' . $feat_tag->name . '</em></a>';
					$feat_tag_header = get_field( 'featured_tag', $home );
					$feat_tag_header = str_replace( '{tag}', $feat_tag_link, $feat_tag_header );

					echo '<h2 class="section_header">' . $feat_tag_header . '</h2>';
					echo '<div class="loop grid row">';
						$tagged_args = array(
							'posts_per_page' => 6,
							'post_type' => 'post',
							'post__not_in' => $already_used,
							'tag' => $feat_tag->slug,
							'date_query' => array (
								'after' => 'December 31, 2012',
							)
						);
						$tagged_query = new WP_Query( $tagged_args );
						if ( $tagged_query->have_posts() ) {
							while ( $tagged_query->have_posts() ) {
								$tagged_query->the_post();
								set_query_var( 'col_size', 'col-12 col-sm-6 col-lg-6 col-xl-4' );
								set_query_var( 'article', $post );
								get_template_part( 'parts/article' );
								$already_used[] = get_the_ID();
							}
						}
						wp_reset_query();
					echo '</div>';
				echo '</section>';
			echo '</div>';
		}

		echo '<div class="col col-12 col-lg-6 col-xl-4">';
			echo '<section id="tags">';
				$more_tags_header = get_field( 'more_tags', $home );
				echo '<h2 class="section_header">' . $more_tags_header . '</h2>';
				echo '<div class="commas tags">';
					$tags = get_recent_tags( 25 );
					foreach( $tags as $tag ) {
						echo '<span>';
							$tag_url = add_query_arg( 'tag', $tag->slug, $articles_url );
							echo '<a href="' . $tag_url . '" class="tag">' . $tag->name . '</a>';
						echo '</span>';
					}
					$tag_page = get_page_by_path( 'tags' );
					$tag_page_url = get_permalink( $tag_page->ID );
					echo '<span class="no_comma no_line">';
						echo '<a href="' . $tag_page_url . '">and more</a>.';
					echo '</span>';
				echo '</div>';
			echo '</section>';
		echo '</div>';

		if( $feat_cols = get_field( 'featured_columns', $current_issue ) ) {
			echo '<section id="featured_columns">';
				$feat_col_i = array_rand( $feat_cols, 1 );
				$feat_col = $feat_cols[$feat_col_i];
				$feat_col_args = array(
					'post_type' => 'post',
					'orderby' => 'date',
					'order' => 'desc',
					'posts_per_page' => 1,
					'post__not_in' => $already_used,
					'tax_query' => array(
						array(
							'taxonomy' => 'columns',
							'field' => 'id',
							'terms' => $feat_col->term_id
						)
					)
				);
				$feat_col_query = new WP_Query( $feat_col_args );
				if ( $feat_col_query->have_posts() ) {
					$feat_col = get_the_terms( $feat_col_query->posts[0], 'columns' )[0];
					$feat_col_name = $feat_col->name;
					$feat_col_slug = $feat_col->slug;
					$feat_col_header = get_field( 'featured_column', $home );
					
					$feat_col_url = add_query_arg( 'column', $feat_col_slug, $page_url );
					$feat_col_link = '<a href="' . $feat_col_url . '"><em>' . $feat_col_name . '</em></a>';
					$feat_col_header = str_replace( '{column}', $feat_col_link, $feat_col_header );

					$feat_col_writer = get_field( 'writer', $feat_col );
					$feat_col_writer_url = $home_url . '/?s=' . urlencode( $feat_col_writer );
					$feat_col_writer_link = '<a href="' . $feat_col_writer_url . '"><em>' . $feat_col_writer . '</em></a>';
					$feat_col_header = str_replace( '{writer}', $feat_col_writer_link, $feat_col_header );					

					echo '<h2 class="section_header">' . $feat_col_header . '</h2>';
					echo '<div class="loop articles one_col masonry">';
						while ( $feat_col_query->have_posts() ) {
							$feat_col_query->the_post();
							set_query_var( 'col_size', 'col-12 col-sm-6 col-md-12 col-lg-6' );
							get_template_part( 'parts/article' );
							$already_used[] = get_the_ID();
						}
					echo '</div>';
				}
				wp_reset_query();
			echo '</section>';
		}
	echo '</div>';
	
	$past_issue = $issues[1];
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
		get_template_part( 'parts/goldbar' );
		echo '<section id="past_issue">';
			$past_issue_header = get_field( 'past_issue', $home );
			$past_issue_title = $past_issue->name;
			$past_issue_date = get_field( 'date', $past_issue );
			$past_issue_url = get_term_link( $past_issue_id, 'issues' );

			$past_issue_title_link = '<a href="' . $past_issue_url . '"><em>' . $past_issue_title . '</em></a>';
			$past_issue_date_link = '<a href="' . $past_issue_url . '"><em>' . $past_issue_date . '</em></a>';

			$past_issue_header = str_replace( '{title}', $past_issue_title_link, $past_issue_header );
			$past_issue_header = str_replace( '{date}', $past_issue_date_link, $past_issue_header );

			echo '<h2 class="section_header">'.$past_issue_header.'</h2>';
			echo '<div class="articles loop row">';
				while ( $past_issue_query->have_posts() ) {
					$past_issue_query->the_post();
					$title = $post->post_title;
					$thumb_id = get_post_thumbnail_id();
					$thumb = wp_get_attachment_image_src( $thumb_id, 'large' );
					$thumb_url = is_array( $thumb ) ? $thumb[0] : false;
					$thumb_width = is_array( $thumb ) ? $thumb[1] : false;
					$thumb_height = is_array( $thumb ) ? $thumb[2] : false;
					$permalink = get_the_permalink();
					$contributors = get_contributors_list( $post->ID, true, true );
					echo '<article class="col col-6 col-sm-4 col-lg-3" role="article">';
						echo '<div class="wrap">';
							echo '<div class="primary">';
								echo '<a class="link_wrap" href="' . $permalink . '">';
									echo '<div class="image load" ' . ( $thumb_url ? 'style="background-image: url(' . $thumb_url . ')"' : '' ) . '>';
									echo '</div>';
								echo '</a>';
							echo '</div>';
							echo '<div class="secondary">';
								echo '<a class="link_wrap" href="' . $permalink . '">';
									echo '<div class="title">';
										echo '<h4>' . $title . '</h4>';
									echo '</div>';
									if( $contributors ) {
										echo '<h4 class="writer">' . $contributors . '</h4>';
									}
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