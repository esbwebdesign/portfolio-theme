<?php
/**
 * The sidebar
 *
 * This is the template that displays the sidebar.
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

declare(strict_types=1);

$base_indent = 8;

$cat_list = wp_list_categories([
	'echo' => false,
	'show_count' => true,
	'title_li' => '',
	'walker' => new Esb_Cat_Walker(),
	// Custom argument to help with indenting properly
	'base_indent' => $base_indent + 1
]);

if ( $cat_list !== false ) {
?>
								<h5>Categories</h5>
								<ul class="list-unstyled"><?php echo $cat_list . "\n"; ?>
								</ul>
<?php
}

$tag_array = get_tags();
if ( !is_wp_error($tag_array) && count($tag_array) > 0 ) {
?>
								<h5>Tags</h5>
<?php
	echo var_dump($tag_array);
}

?>