<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Parsing\ParsingContext;

class SymbolsCalculator {
	private array $_parsers;

	public function __construct(array $parsers = []) {
		$this->_parsers = $parsers;
	}

	public function calculate(array& $strings): float {
		$parsing_context = new ParsingContext($strings);

		foreach ($this->_parsers as $parser) {
			foreach ($parsing_context as $symbol) {
				if ($parser->parse($parsing_context)) break;
			}
		}

		return $parsing_context->result();
	}
}