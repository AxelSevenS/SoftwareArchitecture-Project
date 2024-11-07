<?php

declare(strict_types=1);

require_once dirname(__FILE__) . '/vendor/autoload.php';

use App\Kernel;

$app = new Kernel();

$args = $argv;
array_shift($args);

$app($args);