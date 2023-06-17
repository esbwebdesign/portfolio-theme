<?php
/**
 * The single post file
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

get_header();

?>
					<div class="col-12 col-lg-9">
<?php

// The Loop
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		echo format_post_card(6);
	}
}

?>
					</div>
					<div class="col-12 col-lg-3">
						<div class="card mb-3">
							<div class="card-body">
<?php get_sidebar(); ?>
							</div>
						</div>
					</div>
<?php

get_footer();