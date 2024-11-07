<?php

declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\Parser;
use App\Calculator\Parsing\ParsingContext;

class MultiParser extends Parser {
	private array $_parsers;

	public function __construct(array $parsers) {
		$this->_parsers = $parsers;
	}

	public function parse(ParsingContext $context): bool {
		$parsed = false;
		foreach ($this->_parsers as $parser) {
			foreach ($context as $symbol) {
				$parsed = $parser->parse($context) || $parsed;
			}
		}
		return $parsed;
	}
}