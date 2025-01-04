<?php
/**
 * Abstract class to format taxonomy objects
 */

declare(strict_types=1);

abstract class Esb_Format_Tax {

    public function __construct(protected Esb_Html_Helper $html_helper, protected array $tax) {}

}