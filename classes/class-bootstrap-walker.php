<?php

declare(strict_types=1);

class Bootstrap_Walker extends Walker_Nav_Menu {
	
	// Start and end each level below the top level
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Apply classes to the dropdown menu
		$ul_classes = ['dropdown-menu'];
		
		// Creates the text for ul elements
		$ul = '<ul class="' . implode(' ', $ul_classes) . '">';
		
		// Adds result to the output
		$output .= $n . $indent . $ul;
		
	}
	
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Outputs end ul tag for submenu
		$output .= $n . $indent . '</ul>' . $n . $indent;
		
	}
	
	// Start and end each element
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Set the required classes for li and a elements
		if ($depth === 0) {
			$li_classes = ['nav-item'];
			$a_classes = ['nav-link'];
		} else {
			$li_classes = [];
			$a_classes = ['dropdown-item'];
		}
		
		// Adds a class for nav items with children
		if ($args->walker->has_children) {
			$li_classes[] = 'dropdown';
			$a_classes[] = 'dropdown-toggle';
			$a_controls_insert = ' id="dropdown-id-' . $data_object->ID . '" role="button" data-bs-toggle="dropdown" aria-expanded="false"';
		} else {
			$a_controls_insert = '';
		}
		
		// Creates the text for li elements
		if (count($li_classes) > 0) {
			$li = '<li class="' . implode(' ', $li_classes) . '">';
		} else {
			$li = '<li>';
		}
		
		// Creates the text for a elements
		$a = '<a class="' . implode(' ', $a_classes) . '" href="' . $data_object->url . '"' . $a_controls_insert . '>' . $data_object->title . '</a>';
		
		// Adds result to the output
		$output .= $n . $indent . $li . $a;
		// $output .= var_dump($spacing);
		
	}
	
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
			
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Outputs end li tag for menu items
		$output .= '</li>';
			
	}
	
	private function get_spacing( stdClass $args, int $depth ) : array {
		
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