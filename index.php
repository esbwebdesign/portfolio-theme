<?php

declare(strict_types=1);

get_header();

?>
					<h1 class="my-3">All Posts</h1>
					<div class="col-12 col-lg-9">
<?php

if ( have_posts() ) {
	// If there are posts, create a Post Formatter object
	$post_formatter = new Esb_Format_Post(new Esb_Html_Helper);
	while ( have_posts() ) {
		the_post();
		// TODO: Set this to respond to the reading settings in WordPress WRT whether to use the exceprt or full text
		echo $post_formatter->format_post(6, true);
	}
}

// if ( $wp_query->max_num_pages > 1 ) {
	// If there are multiple pages, create a Pagination Formatter object
	$pagination_formatter = new Esb_Format_Pagination($wp_query);
?>
					<nav aria-label="Page Navigation">
						<ul class="pagination">
<?php echo $pagination_formatter->format_links(7); ?>
						</ul>
					</nav>
<?php
// }

?>
					</div>
					<div class="col-12 col-lg-3">
						<div class="card mb-3">
							<div class="card-body">
								<p>Sidebar goes here</p>
							</div>
						</div>
					</div>
<?php

get_footer();