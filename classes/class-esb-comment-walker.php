<?php

declare(strict_types=1);

class Esb_Comment_Walker extends Walker_Comment {
	
	// Start each level below the top level
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Creates the text for ul elements
		$ul = '<ul class="no-bullets children">';
		
		// Adds result to the output
		$output .= $indent . $ul . N;
		
	}
	
	// End each level below the top level
	public function end_lvl( &$output, $depth = 0, $args = array() ) {
		
		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Outputs end ul tag for submenu
		$output .= $indent . '</ul>' . N;
		
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
		
		// Convert date format
		$comment_date = date( 'F j, Y \a\t g:i a', strtotime( $data_object->comment_date ) );
		
		// Get the comment link
		$comment_href = get_permalink($data_object->comment_post_ID) . '#comment-' . $data_object->comment_ID;
		// Get the reply link
		$reply_href = get_permalink($data_object->comment_post_ID) . '?replytocom=' . $data_object->comment_ID . '#respond';
		
		// Prepare comment text
		$comment = $this->prep_comment($data_object->comment_content);
		
		// Add a class if the comment author is also the post author
		$is_author_class = ( $data_object->user_id === $data_object->comment_post_ID ) ? ' is-author' : '';
		
		if ( $data_object->comment_approved ) {
			$is_unapproved_class = '';
			$display_unapproved = '';
		} else {
			$is_unapproved_class = ' is-unapproved';
			$display_unapproved = $indent . str_repeat( T, 3 ) . '<p><em>Your comment is currently awaiting moderation and can only be seen by you and site administrators.</em></p>' . N;
		}
		
		$card = 
			$indent . str_repeat( T, 1 ) . '<div class="card mb-3' . $is_author_class . $is_unapproved_class . '">' . N . 
			$indent . str_repeat( T, 2 ) . '<div class="card-body">' . N . 
			$indent . str_repeat( T, 3 ) . '<p class="mb-0"><strong>' . $data_object->comment_author . '</strong></p>' . N . 
			$indent . str_repeat( T, 3 ) . '<p class="mt-0 date"><a href="' . $comment_href . '">' . $comment_date . '</a></p>' . N .
			$display_unapproved .
			$indent . str_repeat( T, 3 ) . '<p>' . $comment . '</p>' . N .
			$indent . str_repeat( T, 3 ) . '<p><a href="' . $reply_href . '">Reply</a></p>' . N ;
		
		// echo var_dump($data_object);
		
		// Output the list item
		$output .= $indent . '<li id="comment-' . $data_object->comment_ID . '">' . N . $card;
		
		// Close the card divs if there are children elements
		if ( $args->has_children ) {
			$output .= 
				$indent . str_repeat( T, 2 ) . '</div>' . N . 
				$indent . str_repeat( T, 1 ) . '</div>' . N;
		}
		
	}
	
	// End each element
	public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {

		// For some reason, $args is an array here but not when called from wp_nav_menu
		// Cast $args as an object if it's an associative array
		if ( is_array($args) ) {
			$args = (object) $args;
		}

		// Get spacing information
		$spacing = $this->get_spacing($args, $depth);
		// Convert spacing information into variables
		extract($spacing);
		
		// Close the card divs if there are NO child elements
		// If there are child elements, this would have been closed in start_el
		if ( ! $args->has_children ) {
			$close_card = 
				$indent . str_repeat( T, 2 ) . '</div>' . N . 
				$indent . str_repeat( T, 1 ) . '</div>' . N;
		} else {
			$close_card = '';
		}
		
		// Close the li tag
		$output .= $close_card . $indent . '</li>' . N;
			
	}
	
	private function prep_comment ( string $raw_comment ) : string {
		
		// Set up output
		$output = '';
		
		// Remove unauthorized HTML content
		$output = wp_kses($raw_comment, ALLOWED_TAGS);
		
		// Convert triple backticks to pre tag
		$output = preg_replace('/(`{3})(.*?)(`{3})/si', '<pre>$2</pre>', $output);
		
		// Convert triple backticks to pre tag
		$output = preg_replace('/(`{1})(.*?)(`{1})/si', '<code>$2</code>', $output);
		
		// Add line breaks
		$output = wpautop($output);
		
		return $output;
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
		];
		
	}
	
}