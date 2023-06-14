<?php

declare(strict_types=1);

class Esb_Cat_Walker extends Walker_Category {
	
	// Start each level below the top level
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Creates the text for ul elements
		$ul = '<ul class="no-bullets">';
		
		// Adds result to the output
		$output .= $n . $indent . $ul;
		
	}
	
	// End each level below the top level
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Outputs end ul tag for submenu
		$output .= $n . $indent . '</ul>';
		
	}
	
	// Start each element
	public function start_el( &$output, $data_object, $depth = 0, $args = array(), $current_object_id = 0 ) {
		
		// For some reason, $args is an array here but not when called from wp_nav_menu
		// Cast $args as an object if it's an associative array
		if ( is_array($args) ) {
			$args = (object) $args;
		}
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Create the li tag
		$li = '<li>';
		// Create the taxonomy url
		$cat_link = get_site_url() . '/' . $data_object->taxonomy . '/' . $data_object->slug;
		// Create the link
		$a = '<a href="' . $cat_link . '" class="no-underline link-dark">' . $data_object->name . ' (' . $data_object->count . ')' . '</a>';
		
		// Output the opening tag
		$output .= $n . $indent . $li . $a;
		
	}
	
	// End each element
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		
		$output .= '</li>';
			
	}
	
	private function get_spacing( stdClass|array $args, int $depth ) : array {
		
		// Cast $args as an object if it's an associative array
		if ( is_array($args) ) {
			$args = (object) $args;
		}
		
		// Get base indent value from arguments if available
		if ( isset( $args->base_indent ) && $args->base_indent > 0 ) {
			$base_indent = $args->base_indent;
		} else {
			$base_indent = 0;
		}
		
		// Handle item spacing
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = str_repeat( $t, $depth + $base_indent );
		
		// Retrun an associative array of spacing information
		return [
			'indent' => $indent,
			'n' => $n
		];
		
	}
	
}