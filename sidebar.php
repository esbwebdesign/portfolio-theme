<?php

declare(strict_types=1);

if ( count(get_categories() + get_tags()) ) {
    $html_helper = new Esb_Html_Helper(8);
    $cat_formatter = new Esb_Format_Cats($html_helper, get_categories());
    $sidebar_formatter = new Esb_Format_Sidebar($html_helper, $cat_formatter);
?>
                    <div class="col-12 col-lg-3">
                        <div class="card mb-3">
                            <div class="card-body">
                            <!-- Sidebar start -->
<?php
echo $sidebar_formatter->display_cats(); 
?>
                            <!-- Sidebar end -->
                            </div>
                        </div>
                    </div>
<?php
}