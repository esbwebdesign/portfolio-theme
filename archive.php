<?php
/**
 * The archive file
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

declare(strict_types=1);

get_header();

?>
					<h1 class="my-3"><?php echo the_archive_title(); ?></h1>
					<div class="col-12 col-lg-9">
<?php

// The Loop
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		echo format_post_card(6, true);
	}
}

// Add pagination (if necessary)
if ( $wp_query->max_num_pages > 1 ) {
?>
					<nav aria-label="Page Navigation">
						<ul class="pagination">
							<?php echo format_pagination_links($wp_query, 7); ?>
						</ul>
					</nav>
<?php
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
