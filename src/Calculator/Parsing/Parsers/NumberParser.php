<?php

declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\Parser;
use App\Calculator\Parsing\ParsingContext;

class NumberParser extends Parser {

	public function parse(ParsingContext $context): bool {
		$value = $context->current();
		if (!is_numeric($value)) return false;

		$value = (float) $value;
		$context->collapse_result($value);
		return true;
	}

}