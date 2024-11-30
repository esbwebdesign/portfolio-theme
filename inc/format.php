<?php

declare(strict_types=1);

function indent(int $depth = 0, bool $echo = false) {
	$indent = str_repeat(T, $depth);
	if ( $echo === true ) {
		echo $indent;
	}
	return $indent;
}

// Format the full post
function format_post_card(int $base_indent = 0, bool $preview = false) : string {
	
	// If the function is asked for the preview, show the excerpt
	// Otherwise just show the whole post
	if ( $preview === true ) {
		$content = 
			indent($base_indent + 2) . '<p class="mt-3 mb-2">' . get_the_excerpt() . '</p>' . N . 
			indent($base_indent + 2) . '<p><a href="' . get_permalink( get_the_ID() ) . '">Read More...</a></p>' . N;
	} else {
		$content = apply_filters( 'the_content', get_the_content() );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$content = '<p>' . $content . '</p>' . N;
	}
	
	$tags = format_tags_inline( get_the_tags() );
	$tags_output = ( strlen($tags) > 0 ) ? indent($base_indent + 2) . '<p>' . $tags . '</p>' . N : '';
	
	$output = '';
	$output .= 
		'<div class="card mb-3">' . N . 
		indent($base_indent + 1) . '<div class="card-body">' . N . 
		indent($base_indent + 2) . '<h3 class="mb-0">' . get_the_title() . '</h3>' . N . 
		indent($base_indent + 2) . '<p class="text-muted mb-0">' . get_the_date('F j, Y') . '</p>' . N . 
		$tags_output .  
		$content . 
		indent($base_indent + 2) . '<p class="text-muted"><em>Posted In: ' . format_cats_inline( get_the_category() ) . '</em></p>' . N . 
		indent($base_indent + 1) . '</div>' . N . 
		indent($base_indent, true) . '</div>' . N;
	return $output;
	
}

// Get list of pagination links as objects
// From here: https://developer.wordpress.org/reference/functions/paginate_links/#comment-3862
function wpdocs_get_paginated_links( $query ) : array {
    // When we're on page 1, 'paged' is 0, but we're counting from 1,
    // so we're using max() to get 1 instead of 0
    $currentPage = max( 1, get_query_var( 'paged', 1 ) );

    // This creates an array with all available page numbers, if there
    // is only *one* page, max_num_pages will return 0, so here we also
    // use the max() function to make sure we'll always get 1
    $pages = range( 1, max( 1, $query->max_num_pages ) );

    // Now, map over $pages and return the page number, the url to that
    // page and a boolean indicating whether that number is the current page
    return array_map( function( $page ) use ( $currentPage ) {
        return ( object ) array(
            "isCurrent" => $page == $currentPage,
            "page" => intval($page),
            "url" => get_pagenum_link( $page )
        );
    }, $pages );
}

// Format pagination links
function format_pagination_links(WP_Query $query, int $base_indent = 0, ) : string {
	
	// Gets the array of link info
	$links = wpdocs_get_paginated_links( $query );
	
	// Sets up the output
	$output = '';
	
	// Get current page index
	$current_index = max( 1, get_query_var( 'paged', 1 ) ) - 1;
	
	// Previous control (disables previous link on first page)
	if ( $current_index === 0 ) {
		$a = '<a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
		$output .= '<li class="page-item disabled">' . $a . '</li>' . N;
	} else {
		$a = '<a class="page-link" href="' . $links[$current_index - 1]->url . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
		$output .= '<li class="page-item">' . $a . '</li>' . N;
	}
	
	// Page controls
	foreach ( $links as $link ) {
		$active = ($link->isCurrent) ? ' active' : '';
		$a = '<a class="page-link" href="' . $link->url . '">' . $link->page . '</a>';
		$output .= indent($base_indent) . '<li class="page-item' . $active . '">' . $a . '</li>' . N;
	}
	
	// Next control (disables previous link on last page)
	if ( $current_index + 1 === count($links) ) {
		$a = '<a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">&raquo;</span></a>';
		$output .= indent($base_indent) . '<li class="page-item disabled">' . $a . '</li>' . N;
	} else {
		$a = '<a class="page-link" href="' . $links[$current_index + 1]->url . '" aria-label="Previous"><span aria-hidden="true">&raquo;</span></a>';
		$output .= indent($base_indent) . '<li class="page-item">' . $a . '</li>' . N;
	}
	
	return $output;
	
}

// Format tags for inline presentation
function format_tags_inline( bool|array $tags ) : string {
	
	// Set up output string
	$output = '';
	
	// If there are any tags, continue
	if ($tags) {
	
		// Iterate through array of tags and format them
		foreach ( $tags as $tag ) {
			
			// Add space between tags
			if ( strlen($output) > 0 ) {
				$output .= ' ';
			}
			
			// Create the tag link
			$a = '<a href="' . get_site_url() . '/tag/' . $tag->slug . '" class="text-decoration-none link-light">' . $tag->name . '</a>';
			
			$output .= '<span class="badge bg-secondary">' . $a . '</span>';

		}
	
	}
	
	return $output;
}

// Format tags for inline presentation
function format_cats_inline( array $cats ) : string {
	
	// Set up output string
	$output = '';
	
	// Iterate through array of categories and format them
	foreach ( $cats as $cat ) {
		
		// Add space between tags
		if ( strlen($output) > 0 ) {
			$output .= ', ';
		}
		
		// Create the category link
		$output .= '<a href="' . get_site_url() . '/' . $cat->taxonomy . '/' . $cat->slug . '" class="link-secondary text-decoration-none">' . $cat->name . '</a>';

	}
	
	return $output;
}