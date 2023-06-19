<?php
/**
 * The single post file
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

get_header();

// The Loop
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
?>
					<h1 class="my-3"><?php echo get_the_title(); ?></h1>
					<div class="col-12 col-lg-9">
						<div class="card mb-3">
							<div class="card-body">
								<?php echo the_content() . N; ?>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-3">
						<div class="card mb-3">
							<div class="card-body">
<?php
	}
}

get_sidebar();

?>
							</div>
						</div>
					</div>
<?php

get_footer();