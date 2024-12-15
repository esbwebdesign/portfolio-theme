<?php

declare(strict_types=1);

class Esb_Html_Helper {
    
    function __construct(public int $base_indent = 0) {
        /**
         * Instantiates the class
         * @param int   $base_indent    Default tab indentation value
         */
    }
    
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
                'start' => '<' . $tag_type . $class_f . $attr_f . $id_f . '>',
                'inner_html' => $inner_html,
                'end' => '</' . $tag_type . '>'
            );

            // Return ouptut
            return $return_str ? implode('', $output) : $output;
    }

}