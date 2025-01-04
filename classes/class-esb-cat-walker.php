<?php
/**
 * Extends the WordPress Walker_Category class to format category trees
 */

declare(strict_types=1);

class Esb_Cat_Walker extends Walker_Category {

    public function __construct(private int $base_indent = 0) {}

    private function get_friendly_url(WP_Term $data_object): string {
        /**
         * For some reason, get_category_link doesn't return a friendly URL. This converts the ID-based url to
         * one that uses the slug.
         * 
         * @param WP_Term               $data_object        WP_Term object that contains the category to reference.
         * 
         * @return string               Returns a string with a friendly URL.
         */
        
        $link_arr = explode('=', get_category_link($data_object));
        return $link_arr[0] . '=' . $data_object->slug;

    }

    public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        /**
         * Start of each element.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param object                $data_object        WP_Term object for current element (all taxonomy items are each a WP_Term object).
         * @param int                   $depth              Current depth of walker. Starts at 0 for level of first items.
         * @param null_array            $args               Additional arguments.
         * @param int                   $current_object_id  Current object ID, but not passed in by default.
         */

        // echo var_dump($data_object);

        $a_start = '<a class="text-dark esb-hover-line-only" href="' . $this->get_friendly_url($data_object) . '">';

        $output .= str_repeat(T, $this->base_indent + $depth) . '<li>' . $a_start . $data_object->name . ' (' . $data_object->category_count . ')'. '</a>';
    }

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        /**
         * Start of each level beyond the top level.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param int                   $depth              Current depth of walker. Starts at 0 for first children of top-level items.
         * @param null|array            $args               Additional arguments.
         */

        $output .= N . str_repeat(T, $this->base_indent + $depth) . '<ul class="ms-3 p-0 esb-no-bullets">' . N;

    }

	public function end_lvl( &$output, $depth = 0, $args = null ) {
        /**
         * End of each level beyond the top level.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param int                   $depth              Current depth of walker. Starts at 0 for first children of top-level items.
         * @param null|array|object     $args               Arguments.  This will be an object if invoked with wp_nav_walker but an array
         *                                                  for other uses.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */
		
        $output .= str_repeat(T, $this->base_indent)  . '</ul>';
	}
    
}

// text-dark esb-hover-line-only