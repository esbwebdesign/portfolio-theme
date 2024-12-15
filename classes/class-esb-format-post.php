<?php

declare(strict_types=1);

class Esb_Format_Post {

    // Dependency injection
    public function __construct(private Esb_Html_Helper $html_helper) {}

    private function format_taxonomy_inline(bool|array $items) {
        // Set default condition
        $output = '';

        // Aliases reference to html_helper
        $html_helper = $this->html_helper;

        // Exits if the taxonomy isn't a post tag or category
        // TODO Handle exceptions properly
        if ($items && ! ($items[0]->taxonomy === 'post_tag' || $items[0]->taxonomy === 'category')) {
            return '<p>Unable to use ' . __METHOD__ . ' for ' . $items[0]->taxonomy . ' ' . __METHOD__ . ' only supports tags and categories.</p>';
        }

        if ($items) {
            $el_array = array();

            foreach($items as $item) {

                // Set the classes for this taxonomy class
                $link_classes = ($item->taxonomy === 'post_tag') ? 
                    array('text-decoration-none', 'link-light') : 
                    array('esb-hover-line-only', 'link-secondary');

                // Create anchor tags for each taxonomy entry
                $a_tag = $html_helper->create_html_tag(
                    tag_type: 'a',
                    inner_html: $item->name,
                    classes: $link_classes,
                    attr: array('href' => get_tag_link($item->term_id))
                );

                // Nest post tags within a span tag
                if ($item->taxonomy === 'post_tag') {
                    $span_tag = $html_helper->create_html_tag(
                        tag_type: 'span',
                        inner_html: $a_tag,
                        classes: array('badge', 'bg-secondary')
                    );
                }

                $el_array[] = $span_tag ?? $a_tag;

            }

            // When all tags have been formatted, insert them into a paragraph tag
            if ($item->taxonomy === 'post_tag') {
                $output = '<p>' . implode(' ', $el_array) . '</p>';
            } else {
                $output = '<p class="text-muted"><em>Posted In: ' . implode(', ', $el_array) . '</em></p>';
            }
            
        }

        return $output;
    }

    private function format_post_data(bool $show_excerpt): array {
        /**
         * Formats the inner HTML components of a post (such as title, content, etc)
         * 
         * @return array        Returns associative array of formatted post content
         */

        
        $html_helper = $this->html_helper;
        
        // Get the tags
        $post_tags = $this->format_taxonomy_inline(get_the_tags());
    
        // Get the categories
        // $post_cats = $this->format_cats_inline(get_the_category());
        $post_cats = $this->format_taxonomy_inline(get_the_category());

        // Get the post date
        // Bottom margin changes if there are no tags, so this MUST go after the tags are formatted
        $date_classes = ($post_tags) ? array('text-muted', 'mb-0') : array('text-muted');
        $post_date = $html_helper->create_html_tag(
            tag_type: 'p',
            inner_html: get_the_date('F j, Y'),
            classes: $date_classes
        );
    
        // Get the post title
        $post_title = get_the_title();
        // Format post title as a link if in preview mode
        if ($show_excerpt) {
            $post_title = $html_helper->create_html_tag(
                tag_type: 'a',
                inner_html: $post_title,
                classes: array('text-dark', 'esb-hover-line-only'),
                attr: array('href' => get_permalink())
            );
        }
    
        // Get the post body or excerpt
        // Using apply filters fixes lets line breaks appear correctly
        // in excerpts, but still ensures that this function always
        // returns a string
        $post_body = ($show_excerpt) ? apply_filters('the_excerpt', get_the_excerpt()) : get_the_content();

        // Set the read more link as null, or format if 
        $post_read_more = null;
        if ($show_excerpt) {
            $post_read_more = $html_helper->create_html_tag(
                tag_type: 'a',
                inner_html: 'Read More...',
                attr: array('href' => get_permalink())
            );
        };
    
        return array(
            'title' => $post_title,
            'content' => $post_body,
            'read_more' => $post_read_more,
            'date' => $post_date,
            'cats' => $post_cats,
            'tags' => $post_tags
        );
    }

    function format_post(int $base_indent = 0, bool $show_excerpt = false): string {

        $html_helper = $this->html_helper;
        
        $post_data = $this->format_post_data($show_excerpt);

        // Handle sections that can be omitted to avoid unnecessary white space in code
        $read_more_line = ($post_data['read_more']) ? $html_helper->indent($base_indent + 2) . '<p>' . $post_data['read_more'] . '</p>' . N : '';
        $tags_line = ($post_data['tags']) ? $html_helper->indent($base_indent + 2) . $post_data['tags'] . N : '';
        $cats_line = ($post_data['cats']) ? $html_helper->indent($base_indent + 2) . $post_data['cats'] . N : '';

        $output = 
            $html_helper->indent($base_indent) . '<div class="card mb-3">' . N . 
            $html_helper->indent($base_indent + 1) . '<div class="card-body">' . N .
            $html_helper->indent($base_indent + 2) . '<h3 class="mb-0">' . $post_data['title'] . '</h3>' . N . 
            $html_helper->indent($base_indent + 2) .  $post_data['date'] . N . 
            $tags_line . 
            $html_helper->indent($base_indent + 2) .  $post_data['content'] . 
            $read_more_line . 
            $cats_line .
            $html_helper->indent($base_indent + 1) . '</div>' . N .
            $html_helper->indent($base_indent) . '</div>' . N;

        return $output;
    }

}