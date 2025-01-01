<?php

declare(strict_types=1);

// // Get list of pagination links as objects
// // From here: https://developer.wordpress.org/reference/functions/paginate_links/#comment-3862
// function wpdocs_get_paginated_links( $query ) : array {
//     // When we're on page 1, 'paged' is 0, but we're counting from 1,
//     // so we're using max() to get 1 instead of 0
//     $currentPage = max( 1, get_query_var( 'paged', 1 ) );

//     // This creates an array with all available page numbers, if there
//     // is only *one* page, max_num_pages will return 0, so here we also
//     // use the max() function to make sure we'll always get 1
//     $pages = range( 1, max( 1, $query->max_num_pages ) );

//     // Now, map over $pages and return the page number, the url to that
//     // page and a boolean indicating whether that number is the current page
//     return array_map( function( $page ) use ( $currentPage ) {
//         return ( object ) array(
//             "isCurrent" => $page == $currentPage,
//             "page" => intval($page),
//             "url" => get_pagenum_link( $page )
//         );
//     }, $pages );
// }

class Esb_Format_Pagination {

    // Dependency injection
    public function __construct(private WP_Query $query) {}

    private function pag_links_to_array(): array {
        /**
         * Creates an a array of pagination link data.
         * Modified from here: https://developer.wordpress.org/reference/functions/paginate_links/#comment-3862
         * 
         * @return array    Returns an array of objects with information about each page link, including
         *                  whether the current page is the current one.
         */

        // Alias the query property for use in this function
        $query = $this->query;

        // The paged key in the $wp_query variable works oddly--it shows 0 for the first page
        // but then 2 for the second, etc.  Using max fixes that issue.  If the paged key isn't set
        // (e.g. if pagination isn't necessary), falls back to 1.
        $current_page = max(1, $query->query_vars['paged'] ?? 1);

        // Creates a range that has all possible page numbers.  Guarantees that range will at least
        // contain 1 because max_num_pages is 0 if pagination isn't necessary.
        $pages = range(1, max(1, $query->max_num_pages));

        // Creates an array of objects with information about each page link
        $pag_arr = array_map( function($page) use ($current_page) {
            return (object) array(
                'page' => $page,
                'is_current' => $page === $current_page,
                'url' => get_pagenum_link($page)
            );
        }, $pages);

        return $pag_arr;
    }

    public function format_links(int $base_indent = 0): string {
        /**
         * Formats the pagination links
         * 
         * @param int       $base_indent        Base tabs to use when indenting HTML. Default 0.
         * 
         * @return string   Returns formatted HTML for pagination links.
         */
        
        // Sets up the output
	    $output = '';

        // Get an array of pagination links with data
        $pag_arr = $this->pag_links_to_array();
	
        // Get current page index
        // Zero indexed to match with array of pagination links
        $current_index = max(1, $this->query->query_vars['paged'] ?? 1) - 1;

        // Previous control (disables previous link on first page)
        if ( $current_index === 0 ) {
            $a = '<a class="page-link" tabindex="-1" aria-disabled="true">Previous</a>';
            $output .= str_repeat(T, $base_indent) . '<li class="page-item disabled me-3">' . $a . '</li>' . N;
        } else {
            $a = '<a class="page-link" href="' . $pag_arr[$current_index - 1]->url . '">Previous</a>';
            $output .= str_repeat(T, $base_indent) . '<li class="page-item me-3">' . $a . '</li>' . N;
        }

        // Page controls
        foreach ( $pag_arr as $link ) {
            $active = ($link->is_current) ? ' active' : '';
            $a = '<a class="page-link" href="' . $link->url . '">' . $link->page . '</a>';
            $output .= str_repeat(T, $base_indent) . '<li class="page-item' . $active . '">' . $a . '</li>' . N;
        }

        // Next control (disables previous link on last page)
        if ( $current_index + 1 === count($pag_arr) ) {
            $a = '<a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">Next</span></a>';
            $output .= str_repeat(T, $base_indent) . '<li class="page-item disabled ms-3">' . $a . '</li>' . N;
        } else {
            $a = '<a class="page-link" href="' . $pag_arr[$current_index + 1]->url . '" aria-label="Previous"><span aria-hidden="true">Next</span></a>';
            $output .= str_repeat(T, $base_indent) . '<li class="page-item ms-3">' . $a . '</li>' . N;
        }

        return $output;
    }

}

// Disabled: <a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
// Enabled: <a class="page-link" href="https://esbportfolio.com/" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>

// Disabled: <li class="page-item disabled"><a ... /a></li>
// Enabled: <li class="page-item"><a ... /a></li>

	// // Previous control (disables previous link on first page)
	// if ( $current_index === 0 ) {
	// 	$a = '<a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
	// 	$output .= '<li class="page-item disabled">' . $a . '</li>' . N;
	// } else {
	// 	$a = '<a class="page-link" href="' . $links[$current_index - 1]->url . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>';
	// 	$output .= '<li class="page-item">' . $a . '</li>' . N;
	// }

    // public function create_html_tag(
    //     string $tag_type, 
    //     bool $return_str = true,
    //     string $inner_html = '',
    //     array $ids = array(),
    //     array $classes = array(),
    //     array $attr = array()
    // ): string|array {
    // /**
    //  * Creates an HTML tag with the specified parameters
    //  * 
    //  * @param string    $tag_type    Type of html tag. Required.
    //  * @param bool      $return_str  Whether to return as string or array. Default true returns as string. False returns associative array.
    //  * @param string    $inner_html  Content to go within tag. Default is empty string.
    //  * @param array     $ids         Array of IDs to apply to tag. Keys will be ignored. Default (empty array) will omit ID statement.
    //  * @param array     $classes     Array of classes to apply to tag. Keys will be ignored. Default (empty array) will omit class statement.
    //  * @param array     $attr        Array of attribtues to apply to tag. Associative array required to work properly. Keys will be used as attribute type and value as attribute value.
    //  * 
    //  * @return string|array          Returns associative array or html string
    //  */