<?php
/**
 * Class to format categories
 */

declare(strict_types=1);

class Esb_Format_Cats extends Esb_Format_Tax {

    public function format_cat_tree(int $base_indent = 0): string {
        
        return wp_list_categories(array(
            'echo' => false,
            'show_count' => true,
            'title_li' => '',
            'walker' => new Esb_Cat_Walker($base_indent)
        ));

    }

    public function format_cats_inline(int $base_indent = 0) {
        echo var_dump($this->tax);
    }

}