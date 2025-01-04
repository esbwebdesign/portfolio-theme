<?php

declare(strict_types=1);

get_header();

?>
					<div class="col-12 <?php echo (count(get_categories() + get_tags()) > 0) ? 'col-lg-9' : ''; ?>">
<?php

if ( have_posts() ) {
	// If there are posts, create a Formatter object
	$post_formatter = new Esb_Format_Post(new Esb_Html_Helper);
	while ( have_posts() ) {
		the_post();
		echo $post_formatter->format_post(6);
	}
}
?>
					</div>
<?php

get_sidebar();

get_footer();