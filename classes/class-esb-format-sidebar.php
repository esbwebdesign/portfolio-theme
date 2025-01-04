<?php
/**
 * Formats sidebar contents for display
 */

declare(strict_types=1);

class Esb_Format_Sidebar {

    // Dependency injection
    public function __construct(private Esb_Format_Cats $cat_formatter) {}

    public function display_cats(int $base_indent = 0): string {
        
        // Set default value to return (empty string)
        $output = '';

        // If there are no categories, return default value
        if (!get_categories()) {
            return $output;
        }

        $output .= str_repeat(T, $base_indent) . '<h5>Categories</h5>' . N;

        $cat_entries = $this->cat_formatter->format_cat_tree(9);

        $output .= str_repeat(T, $base_indent) . '<ul class="list-unstyled">' . N . $cat_entries . N . str_repeat(T, $base_indent) . '</ul>' . N;

        return $output;
    }

}