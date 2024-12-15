<?php

declare(strict_types=1);

get_header();

if ( have_posts() ) {
	// If there are posts, create a Formatter object
	$post_formatter = new Esb_Format_Post(new Esb_Html_Helper);
	while ( have_posts() ) {
		the_post();
		echo $post_formatter->format_post(6);
	}
}

get_footer();