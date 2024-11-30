<?php

declare(strict_types=1);

class Esb_Nav_Walker extends Walker_Nav_Menu {
	
	// Start each level below the top level
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Apply classes to the dropdown menu
		if ( $depth === 0 ) {
			$ul_classes = ['dropdown-menu'];
		} else {
			$ul_classes = ['submenu', 'list-unstyled', 'ms-3'];
		}
		
		// Creates the text for ul elements
		$ul = '<ul class="' . implode(' ', $ul_classes) . '">';
		
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
		$output .= $n . $indent . '</ul>' . $n . $indent;
		
	}
	
	// Start each element
	public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
		
		// Cast $args as an object if it's an associative array
		if ( is_array($args) ) {
			$args = (object) $args;
		}
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Set the required classes for li and a elements
		if ($depth === 0) {
			$li_classes = ['nav-item'];
			$a_classes = ['nav-link'];
			// Add additional dropdown classes for top level elements with children
			if ( $args->walker->has_children ) {
				$li_classes[] = 'dropdown';
				$a_classes[] = 'dropdown-toggle';
			}
		} else {
			$li_classes = [];
			$a_classes = ['dropdown-item'];
		}
		
		// Creates the text for li elements
		if (count($li_classes) > 0) {
			$li = '<li class="' . implode(' ', $li_classes) . '">';
		} else {
			$li = '<li>';
		}
		
		// Set the default output for links
		$a = '<a class="' . implode(' ', $a_classes) . '" href="' . $data_object->url . '">' . $data_object->title . '</a>';
		
		// Override default link output
		if ( $args->walker->has_children ) {
			// If this is a top-level menu item with children, format as a dropdown control
			if ( $depth === 0 ) {
				$a_controls_insert = ' id="dropdown-id-' . $data_object->ID . '" role="button" data-bs-toggle="dropdown" aria-expanded="false"';
				$a = '<a class="' . implode(' ', $a_classes) . '" href="' . $data_object->url . '"' . $a_controls_insert . '>' . $data_object->title . '</a>';
			// If this is a dropdown menu item with children, and without a URL of its own, format it as a span
			} else {
				if ( $data_object->url === '#' ) {
					$a = '<span class="ms-3">' . $data_object->title . '</span>';
				}
			}
		}
		
		// Adds result to the output
		$output .= $n . $indent . $li . $a;
		
	}
	
	// End each element
	public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
		
		// Outputs end li tag for menu items
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