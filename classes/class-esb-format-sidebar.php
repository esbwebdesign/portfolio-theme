<?php
/**
 * Formats sidebar contents for display
 */

declare(strict_types=1);

class Esb_Format_Sidebar {

    // Dependency injection
    public function __construct(
        private Esb_Html_Helper $html_helper,
        private Esb_Format_Cats $cat_formatter,
    ) {}

    public function display_cats(string $header_text = 'Categories', string $h_tag = 'h5'): string {
        
        // Set default value to return (empty string)
        $output = '';

        $base_indent = $this->html_helper->base_indent;

        // If there are no categories, return default value
        if (!get_categories()) {
            return $output;
        }

        // Build and insert header
        $header = $this->html_helper->create_html_tag(
            tag_type: $h_tag,
            inner_html: $header_text
        );
        $output .= str_repeat(T, $base_indent) . $header . N;

        // Build category tree
        $cat_entries = $this->cat_formatter->format_cat_tree();

        // Build ul to contain category tree
        $ul = $this->html_helper->create_html_tag(
            tag_type: 'ul',
            return_str: false,
            classes: array('list-unstyled')
        );

        // Insert elements
        $output .= str_repeat(T, $base_indent) . $ul['start'] . N . $cat_entries . str_repeat(T, $base_indent) . $ul['end'] . N;

        return $output;
    }

}


// public function create_html_tag(
//     string $tag_type, 
//     bool $return_str = true,
//     string $inner_html = '',
//     array $ids = array(),
//     array $classes = array(),
//     array $attr = array()
// )