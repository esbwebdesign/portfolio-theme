<?php
/**
 * Class to format categories
 */

declare(strict_types=1);

class Esb_Format_Cats extends Esb_Format_Tax {
    /**
     * Class that formats categories. Extended from Esb_Format_Tax
     * 
     * @var Esb_Html_Helper     $html_helper    HTML helper class. Inherited from parent class via constructor.
     * @var array               $tax            Array of WP_Term objects. Inherited from parent class via constructor.
     */

    public function format_cat_tree(): string {
        
        return wp_list_categories(array(
            'echo' => false,
            'show_count' => true,
            'title_li' => '',
            'walker' => new Esb_Cat_Walker($this->html_helper, 1)
        ));

    }

    public function format_cats_inline(int $base_indent = 0) {
        echo var_dump($this->tax);
    }

}