<?php
echo '<div id="popup" class="newsletter">';
	echo '<div class="inner">';
		get_template_part( 'parts/goldbar' );
		get_template_part( 'parts/newsletter' );
		echo '<div class="close circle">';
			echo get_svg( 'x' );
		echo '</div>';
	echo '</div>';
echo '</div>';
?>