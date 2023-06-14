<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

get_header();

?>
		<main>
			<div class="container mt-3">
				<div class="row">
					<h1 class="my-3">All Posts</h1>
					<div class="col-12 col-lg-9">
<?php

// The Loop
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post(); 
?>
						<div class="card mb-3 esb-post-card">
							<div class="card-body">
								<h3><?php the_title(); ?></h3>
								<?php the_excerpt(); ?>
								<p><a href="<?php echo get_permalink( get_the_ID() ); ?>">Read More...</a></p>
							</div>
						</div>
<?php
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
				</div>
			</div>
		</main>
<?php

get_footer();

?>