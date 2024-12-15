<?php

declare(strict_types=1);

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
				<!-- TODO: Add logo support -->
				<a class="navbar-brand" href="<?php echo home_url(); ?>"><?php echo get_bloginfo('name'); ?></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ms-auto">
<?php
wp_nav_menu(array(
	'menu' => 'primary-menu',
	'container' => false,
	'items_wrap' => '%3$s',
	'walker' => new Esb_Nav_Walker(new Esb_Html_Helper(6)),
));
?>
					<form class="d-flex" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
						<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" value="<?php echo get_search_query(); ?>" name="s">
						<button class="btn btn-success" type="submit" value="Search" >Search</button>
					</form>
					</ul>
				</div>
			</div>		
		</nav>
		<main>
			<pre>
<?php


?>
			</pre>
			<div class="container mt-3">
				<div class="row">
