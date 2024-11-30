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

$base_indent = 9;
$n = "\n";
$t = "\t";

// Set up walker to handle category list
$cat_list = wp_list_categories([
	'echo' => false,
	'show_count' => true,
	'title_li' => '',
	'walker' => new Esb_Cat_Walker(),
	// Custom argument to help with indenting properly
	'base_indent' => $base_indent + 1
]);

// Set up tag array
$tag_array = get_tags();
$tag_output = '';
// Create a set of links from the tags
foreach ( $tag_array as $tag ) {
	// Add a space between links
	if ( strlen($tag_output) !== 0 ) {
		$tag_output .= ' ';
	}
	$tag_link = get_site_url() . '/tag/' . $tag->slug;
	$a = '<a href="' . $tag_link . '" class="text-decoration-none link-light">' . $tag->name . '</a>';
	$tag_output .= $n . str_repeat($t, $base_indent + 1) . '<span class="badge bg-secondary">' . $a . '</span>';
}
$tag_output .= $n . str_repeat($t, $base_indent);

if ( $cat_list !== false ) {
?>
								<div class="mb-4">
									<h5>Categories</h5>
									<ul class="list-unstyled"><?php echo $cat_list . "\n"; ?>
									</ul>
								</div>
<?php
}

if ( !is_wp_error($tag_array) && count($tag_array) > 0 ) {
?>
								<div class="mb-4">
									<h5>Tags</h5>
									<div><?php echo $tag_output; ?></div>
								</div>
<?php
}

?>