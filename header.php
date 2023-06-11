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
	'items_wrap' => str_repeat( "\t", $base_indent ) . '<ul class="%2$s">%3$s' . "\n" . str_repeat( "\t", $base_indent ) . '</ul>',
	'walker' => new Bootstrap_Walker(),
	'echo' => false,
	// Custom argument to help with indenting properly
	'base_indent' => $base_indent + 1
]);

?>
<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Begin Wordpress header -->
<?php wp_head(); ?>
<!-- End Wordpress header -->
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<div class="container">
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
<?php echo $main_menu . "\n"; ?>
				</div>
			</div>
		</nav>
