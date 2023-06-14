<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

declare(strict_types=1);

$base_indent = 5;

$main_menu = wp_nav_menu([
	'menu' => 'Primary Menu',
	'container' => false,
	'container_class' => 'navbar navbar-expand-lg navbar-dark bg-dark',
	'menu_class' => 'navbar-nav',
	'items_wrap' => str_repeat( "\t", $base_indent ) . '<ul class="%2$s ms-auto">%3$s' . "\n" . str_repeat( "\t", $base_indent ) . '</ul>',
	'walker' => new Esb_Nav_Walker(),
	'echo' => false,
	// Custom argument to help with indenting properly
	'base_indent' => $base_indent + 1
]);

if ( strlen(get_site_icon_url()) > 0 ) {
	$brand_img = ' <img src="' . get_site_icon_url() . '" alt="' . get_bloginfo('name') . ' logo" height="42px">';
	$brand_insert = '<a class="navbar-brand" href="' . get_site_url() . '">' . $brand_img . '</a>' . "\n";
} else {
	$brand_insert = '';
}

?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Begin Wordpress header -->
<?php wp_head(); ?>
<!-- End Wordpress header -->
	</head>
	<body class="bg-light">
		<nav class="navbar navbar-expand-lg navbar-light bg-light">
			<div class="container">
				<?php echo $brand_insert; ?><a class="navbar-brand" href="<?php echo get_site_url(); ?>"><?php echo get_bloginfo('name'); ?></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
<?php echo $main_menu . "\n"; ?>
					<form class="d-flex" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" value="<?php echo get_search_query(); ?>" name="s">
						<button class="btn btn-success" type="submit" value="Search" >Search</button>
					</form>
				</div>
			</div>
		</nav>
