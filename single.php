<?php

declare(strict_types=1);

get_header();

if ( have_posts() ) {
	// If there are posts, create a Formatter object
	$formatter = new Esb_Formatter();
	while ( have_posts() ) {
		the_post();
		echo $formatter->format_post(6, false);
	}
}

get_footer();