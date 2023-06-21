<?php
/**
 * The 404 file
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

get_header();

?>
					<h1 class="my-3">Page Not Found</h1>
					<div class="col-12 col-lg-9">
						<div class="card">
							<div class="card-body">
								<p>The page you requested could not be found.  Please try again.</p>
								<p><a href="<?php echo get_site_url(); ?>">Return Home</a></p>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-3">
						<div class="card mb-3">
							<div class="card-body">
<?php get_sidebar(); ?>
							</div>
						</div>
					</div>
<?php

comments_template();

get_footer();