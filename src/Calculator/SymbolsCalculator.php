<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Parsing\ParsingContext;
use App\Calculator\Parsing\Parser;
use App\Calculator\Parsing\Parsers\MultiParser;

class SymbolsCalculator {
	private MultiParser $_parser;

	public function __construct(Parser|array $parser) {
		if (!is_array($parser)) {
			$parser = [$parser];
		}

		$this->_parser = new MultiParser($parser);
	}

	public function calculate(array& $strings): float {
		$parsing_context = new ParsingContext($strings);

		$this->_parser->parse($parsing_context);

		return $parsing_context->result();
	}
}