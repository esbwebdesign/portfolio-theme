<?php
/**
 * The comments
 *
 * @package WordPress
 * @subpackage ESB Portfolio
 */

declare(strict_types=1);

// Comments should be shown if commetns are open OR if there's at least one approved comment
$show_comments = comments_open() || get_approved_comments() > 0;

$comments_list = wp_list_comments([
	'walker' => new Esb_Comment_Walker(),
	'echo' => false,
	'base_indent' => 7
]);

// If comments should be shown, create a row for them
if ( $show_comments	 ) {
?>
				</div>
				<div class="row">
					<div class="col-12 col-lg-9">
						<h5>Comments</h5>
						<p>Please use single backticks (`) for inline code and triple backticks (```) to enclose block code.</p>
<?php

	// If there are comments, show them
	if ( $comments_list ) {
?>
						<ul class="list-unstyled comments">
<?php
		echo $comments_list;
?>
						</ul>
<?php
	}

?>
					<!-- Comment form starts -->
<?php
	
	// If comments are open, show the comment form
	if ( comments_open() ) {
		
		comment_form([
			'fields' => [
				'author' => 
					'<div class="mb-3">' . N .
					indent(1) . '<label class="form-label" for="comment-name">Name *</label>' . N .
					indent(1) . '<input type="text" id="comment-name" class="form-control" name="author" required="required" maxlength="245" autocomplete="name" value="' . esc_attr( $commenter['comment_author'] ) . '" >' . N .
					'</div>',
				// 'email' => 
					// '<div class="mb-3">' . N .
					// indent(1) . '<label class="form-label" for="comment-email">Email *</label>' . N .
					// indent(1) . '<input type="email" id="comment-email" class="form-control" name="email" required="required" maxlength="100" autocomplete="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" required>' . N .
					// '</div>',
				'cookies' => 
					'<div class="mb-3">' . N .
					indent(1) . '<input type="checkbox" class="form-check-input" id="cookie-consent" name="wp-comment-cookies-consent" checked>' . N .
					indent(1) . '<label class="form-check-label" for="cookie-consent">Save my name in this browser for the next time I comment.</label>' . N .
					'</div>',
			],
			'comment_field' => 
				'<div class="mb-3">' . N .
				indent(1) . '<label class="form-label" for="comment-body">Comment *</label>' . N .
				indent(1) . '<textarea type="text" id="comment-body" class="form-control" name="comment" required="required" rows="4" maxlength="65525" required></textarea>' . N .
				'</div>',
			'comment_notes_before' => N . '<p>We collect some information from your browser (such as IP address) to help with spam detection.  This information is visible to site administrators.  See our <a href="https://esbportfolio.com/privacy-policy/">Privacy Policy</a> here.</p><p>Comments are moderated, so there may be a delay before your comment appears.</p>' . N,
			'class_submit' => 'btn btn-primary',
			'title_reply_before' => '<p class="mb-0"><strong>',
			'title_reply_after' => '</strong></p>' . N,
		]);
	}
?>
					<!-- Comment form ends -->
					</div>
<?php
}
?>