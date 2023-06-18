<?php

declare(strict_types=1);

// Root prefix for site
define('__ROOT__', dirname(dirname(__FILE__)));

// Spacing contants
define('N', "\n");
define('T', "\t");

// Allowable HTML
define('ALLOWED_TAGS', [
	'a' => ['href' => []],
	'b' => [],
	'del' => [],
	'em' => [],
	'i' => [],
	's' => [],
	'strong' => []
]);
