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
		<nav><a href="<?php echo home_url(); ?>">Navigation Placeholder</a></nav>
		<main>
			<p><?php echo get_bloginfo('name'); ?></p>
			<div class="container mt-3">
				<div class="row">
