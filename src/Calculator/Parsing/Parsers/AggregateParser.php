<?php

declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\Parser;
use App\Calculator\Parsing\ParsingContext;

class AggregateParser implements Parser {
	private array $_parsers;

	public function __construct(array $parsers) {
		$this->_parsers = $parsers;
	}

	public function parse(ParsingContext $context): bool {
		$parsed = false;
		foreach ($this->_parsers as $parser) {
			$parsed = $parser->parse($context) || $parsed;
		}
		return $parsed;
	}
}