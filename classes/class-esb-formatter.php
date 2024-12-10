<?php

declare(strict_types=1);

class Esb_Formatter {
    
    public function indent(int $depth = 0, bool $echo = false): string|null {
        /**
         * Produces tab indentation of the specified depth
         * 
         * @param int   $depth  Depth of indentation. Default 0.
         * @param bool  $echo   Whether response should be echoed. Default false.
         * 
         * @return string|null  Returns string if echo is false, null if echo is true
         */
        $output = str_repeat(T, $depth);
        if ( $echo === true ) {
            echo $output;
        }
        return $output;
    }

    public function create_html_tag(
            string $tag_type, 
            bool $return_str = true,
            string $inner_html = '',
            array $ids = array(),
            array $classes = array(),
            array $attr = array()
        ): string|array {
        /**
         * Creates an HTML tag with the specified parameters
         * 
         * @param string    $tag_type    Type of html tag. Required.
         * @param bool      $return_str  Whether to return as string or array. Default true returns as string. False returns associative array.
         * @param string    $inner_html  Content to go within tag. Default is empty string.
         * @param array     $ids         Array of IDs to apply to tag. Keys will be ignored. Default (empty array) will omit ID statement.
         * @param array     $classes     Array of classes to apply to tag. Keys will be ignored. Default (empty array) will omit class statement.
         * @param array     $attr        Array of attribtues to apply to tag. Associative array required to work properly. Keys will be used as attribute type and value as attribute value.
         * 
         * @return string|array          Returns associative array or html string
         */

            // Format IDs
            $id_f = $ids ? ' id="' . implode(' ', $ids) . '"' : '';

            // Format classes
            $class_f = $classes ? ' class="' . implode(' ', $classes) . '"' : '';

            // Format attributes
            $attr_f = '';
            foreach ($attr as $key => $value) {
                $attr_f .= " ${key}=\"$value\"";
            }

            // Create tag array
            $output = array(
                'start' => '<' . $tag_type . $attr_f . $class_f . $id_f . '>',
                'inner_html' => $inner_html,
                'end' => '</' . $tag_type . '>'
            );

            // Return ouptut
            return $return_str ? implode('', $output) : $output;
    }

    private function format_taxonomy_inline(bool|array $items) {
        // Set default condition
        $output = '';

        // Exits if the taxonomy isn't a post tag or category
        // TODO Handle exceptions properly
        if ($items && ! ($items[0]->taxonomy === 'post_tag' || $items[0]->taxonomy === 'category')) {
            return '<p>Unable to use ' . __METHOD__ . ' for ' . $items[0]->taxonomy . ' ' . __METHOD__ . ' only supports tags and categories.</p>';
        }

        if ($items) {
            $el_array = array();

            foreach($items as $item) {

                // // Add space between items in the taxonomy list
                // if ( strlen($output) > 0 ) {
                //     $output .= ' ';
                // }

                // Set the classes for this taxonomy class
                $link_classes = ($item->taxonomy === 'post_tag') ? 
                    array('text-decoration-none', 'link-light') : 
                    array('esb-hover-line-only', 'link-secondary');

                // Create anchor tags for each taxonomy entry
                $a_tag = $this->create_html_tag(
                    tag_type: 'a',
                    inner_html: $item->name,
                    classes: $link_classes,
                    attr: array('href' => get_tag_link($item->term_id))
                );

                // Nest post tags within a span tag
                if ($item->taxonomy === 'post_tag') {
                    $span_tag = $this->create_html_tag(
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
    
        // Get the tags
        $post_tags = $this->format_taxonomy_inline(get_the_tags());
    
        // Get the categories
        // $post_cats = $this->format_cats_inline(get_the_category());
        $post_cats = $this->format_taxonomy_inline(get_the_category());

        // Get the post date
        // Bottom margin changes if there are no tags, so this MUST go after the tags are formatted
        $date_classes = ($post_tags) ? array('text-muted', 'mb-0') : array('text-muted');
        $post_date = $this->create_html_tag(
            tag_type: 'p',
            inner_html: get_the_date('F j, Y'),
            classes: $date_classes
        );
    
        // Get the post title
        $post_title = get_the_title();
        // Format post title as a link if in preview mode
        if ($show_excerpt) {
            $post_title = $this->create_html_tag(
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
            $post_read_more = $this->create_html_tag(
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
        
        $post_data = $this->format_post_data($show_excerpt);

        // Handle sections that can be omitted to avoid unnecessary white space in code
        $read_more_line = ($post_data['read_more']) ? $this->indent($base_indent + 2) . '<p>' . $post_data['read_more'] . '</p>' . N : '';
        $tags_line = ($post_data['tags']) ? $this->indent($base_indent + 2) . $post_data['tags'] . N : '';
        $cats_line = ($post_data['cats']) ? $this->indent($base_indent + 2) . $post_data['cats'] . N : '';

        $output = 
            $this->indent($base_indent) . '<div class="card mb-3">' . N . 
            $this->indent($base_indent + 1) . '<div class="card-body">' . N .
            $this->indent($base_indent + 2) . '<h3 class="mb-0">' . $post_data['title'] . '</h3>' . N . 
            $this->indent($base_indent + 2) .  $post_data['date'] . N . 
            $tags_line . 
            $this->indent($base_indent + 2) .  $post_data['content'] . 
            $read_more_line . 
            $cats_line .
            $this->indent($base_indent + 1) . '</div>' . N .
            $this->indent($base_indent) . '</div>' . N;

        // $output = $cats_line;

        return $output;
    }

}