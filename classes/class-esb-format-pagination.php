<?php
/**
 * Formats pagination for display
 */

declare(strict_types=1);

class Esb_Format_Pagination {

    private int $max_pag_links;

    // Dependency injection
    public function __construct(private WP_Query $query, int $max_pag_links = 4) {
        /**
         * @param WP_Query      $query              Reference to the current query
         * @param int           $max_pag_links      Maximum number of links to show without a gap. Default 6. Minimum 4.
         */

        // Ensure that the maximium number of links is even
        if ($max_pag_links % 2 != 0) {
            $max_pag_links -= 1;
        }

        // Make sure that the number of links is at least 4
        $this->max_pag_links = max($max_pag_links, 4);

        // Get the current page
        $this->current_page = max(1, $this->query->query_vars['paged'] ?? 1);

        // Check if this is the first page
        $this->is_first_page = $this->current_page === 1;

        // Check if this is the last page
        $this->is_last_page = $this->current_page === $this->query->max_num_pages;

    }
    

    private function map_pages_by_num(array $page_range, $current_page): array {
        /**
         * Uses the page range provided to return an array of objects with information about those pages 
         * to be used in pagination.
         * 
         * @param array     $page_range     Array of pages to use as a map
         * 
         * @return array    Array of objects with page number, whether page is current, page URL; also indicates not placeholder
         */

        // Return an array of link data based on the page range passed into this function
        return array_map( function($page) {
            return (object) array(
                'page_num' => $page,
                'is_current' => $page === $this->current_page,
                'url' => get_pagenum_link($page),
                'is_placeholder' => false
            );
        }, $page_range);

    }

    private function get_link_array(): array {
        /**
         * Creates an array of objects with information about links and placeholders
         * 
         * @return array    Array of objects with information needed to format pagination
         */
        
        // Alias references to properties
        $query = $this->query;
        $max_pag_links = $this->max_pag_links;
        $current_page = $this->current_page;

        // Get the number of pages
        // Returns 0 if no pagination, so using max ensures that there's at least 1 page
        $total_pages = max(1, $query->max_num_pages);

        // If all pagination links can be shown, get the array of link info and return
        if ($total_pages <= $this->max_pag_links) {
            // Primary page range is all the pages
            $page_range = range(1, $total_pages);
            $link_array = $this->map_pages_by_num($page_range, $current_page);
            return $link_array;
        }

        // If there are more pagination links than can be shown

        // Create an object that will be used to mark where there's a gap placeholder
        $gap_obj = (object) array(
            'is_placeholder' => true
        );

        // If at the start of the range, show gap before last page
        if ($current_page < $max_pag_links) {
            // Primary page range is 1 to the max number of links that can be shown in a block
            $page_range = range(1, $max_pag_links);
            // Add a gap and then the final page
            // Example: 1 2 3 4 5 6 ... 18
            $link_array = array(
                ...$this->map_pages_by_num($page_range, $current_page),
                $gap_obj,
                ...$this->map_pages_by_num(array($total_pages), $current_page),
            );
            return $link_array;
        }

        // If in the middle of the range, show gap at start and end
        // Number of links to show by side of current link depends on maximum links to show
        $number_side_links = ($max_pag_links - 2) / 2;
        if ($current_page < $total_pages - $number_side_links - 1) {
            // Primary page range centers on the current page, with the number of links above to either side
            $page_range = range($current_page - $number_side_links, $current_page + $number_side_links);
            // Center the current page and show the first and last page with gaps between
            // Example: 1 ... 4 5 6 7 8 ... 18
            $link_array = array(
                ...$this->map_pages_by_num(array(1), $current_page),
                $gap_obj,
                ...$this->map_pages_by_num($page_range, $current_page),
                $gap_obj,
                ...$this->map_pages_by_num(array($total_pages), $current_page)
            );
            return $link_array;
        }
        
        // If at the end (fallback condition), show gap after first page
        // Primary page range is from one less than the maximum links that can be shown to the end of the pages
        $page_range = range($total_pages - $max_pag_links + 1, $total_pages);
        $link_array = array(
            ...$this->map_pages_by_num(array(1), $current_page),
            $gap_obj,
            ...$this->map_pages_by_num($page_range, $current_page)
        );
        // Show the first page, then a gap, and then the final pages
        // Example: 1 ... 13 14 15 16 17 18
        return $link_array;
    }
    
    public function format_links(int $base_indent = 0): string {
        /**
         * Formats pagination links and outputs HTML
         * 
         * @param $base_indent      int     Base indent for code indentation. Default 0.
         * 
         * @return string           Formatted HTML for pagination
         */

        // Set up starting output
        $output = '';

        // Get array of link data
        $link_data = $this->get_link_array();

        // Create previous page control (disabled on first page)
        if ($this->is_first_page) {
            $prev_ctrl = '<li class="page-item disabled"><a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Previous"><span aria-hidden="true">&lt;</span></a></li>';
        } else {
            $prev_ctrl = '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($this->current_page - 1) . '" aria-label="Previous"><span aria-hidden="true">&lt;</span></a></li>';
        }

        // Create next page control (disabled on last page)
        if ($this->is_last_page) {
            $next_ctrl = '<li class="page-item disabled"><a class="page-link" tabindex="-1" aria-disabled="true" aria-label="Next"><span aria-hidden="true">&gt;</span></a></li>';
        } else {
            $next_ctrl = '<li class="page-item"><a class="page-link" href="' . get_pagenum_link($this->current_page + 1) . '" aria-label="Next"><span aria-hidden="true">&gt;</span></a></li>';
        }

        // Insert previous control
        $output .= str_repeat(T, $base_indent) . $prev_ctrl . N;

        // Insert page links and placeholders
        foreach ($link_data as $page) {

            // If the page isn't a gap placeholder, create the page link
            if (!$page->is_placeholder) {
                $a = '<a class="page-link" href="' . $page->url . '">' . $page->page_num . '</a>';
                $active_class = ($page->is_current) ? ' active' : '';
                $li = '<li class="page-item' . $active_class . '" aria-current="page">' . $a . '</li>';
            // If it is, create the elipsis link
            } else {
                $a = '<a class="page-link border-0 bg-transparent text-dark" tabindex="-1" aria-label="ellipses" aria-disabled="true">...</a>';
                $li = '<li class="page-item disabled">' . $a . '</li>';
            }

            // Insert the pagination item
            $output .= str_repeat(T, $base_indent) . $li . N;
        }

        // Insert next control
        $output .= str_repeat(T, $base_indent) . $next_ctrl . N;

        return $output;

    }

}