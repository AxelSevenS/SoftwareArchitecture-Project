<?php

declare(strict_types=1);

namespace App\Calculator\Parsing;

use App\Calculator\Parsing\ParsingContext;

interface Parser {
	function parse(ParsingContext $context): bool;
}