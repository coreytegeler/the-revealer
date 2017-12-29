<?php
echo '<div id="popup" class="newsletter">';
	echo '<div class="inner">';
		get_template_part( 'parts/goldbar' );
		get_template_part( 'parts/newsletter' );
		echo '<div class="close circle">';
			$x_svg = get_template_directory_uri() . '/assets/images/x.svg';
			echo file_get_contents( $x_svg );
		echo '</div>';
	echo '</div>';
echo '</div>';
?>