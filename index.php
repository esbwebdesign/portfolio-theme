<?php

declare(strict_types=1);

get_header();

?>
					<h1 class="my-3">All Posts</h1>
					<div class="col-12 col-lg-9">
<?php

if ( have_posts() ) {
	// If there are posts, create a Formatter object
	$post_formatter = new Esb_Format_Post(new Esb_Html_Helper);
	while ( have_posts() ) {
		the_post();
		echo $post_formatter->format_post(6, true);
		// echo '<pre>';
		// echo var_dump(get_the_tags());
		// echo '</pre>';
	}
}

?>
					</div>
<?php

get_footer();