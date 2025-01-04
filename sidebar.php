<?php

declare(strict_types=1);

if ( count(get_categories() + get_tags()) ) {
    $cat_formatter = new Esb_Format_Cats(new Esb_Html_Helper, get_categories());
    $sidebar_formatter = new Esb_Format_Sidebar($cat_formatter);
?>
                    <div class="col-12 col-lg-3">
                        <div class="card mb-3">
                            <div class="card-body">
                            <!-- Sidebar start -->
<?php
echo $sidebar_formatter->display_cats(8); 
?>
                            <!-- Sidebar end -->
                            </div>
                        </div>
                    </div>
<?php
}