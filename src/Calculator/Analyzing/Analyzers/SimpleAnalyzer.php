<?php
declare(strict_types=1);

namespace App\Calculator\Analyzing\Analyzers;

use App\Calculator\Parsing\ParsingContext;
use App\Calculator\Parsing\Parsers\ParserCollection;
use App\Calculator\Analyzing\SyntaxAnalyzer;

class SimpleAnalyzer implements SyntaxAnalyzer {
	protected ParserCollection $_parser_collection;

	public function __construct(ParserCollection $parsers) {
		$this->_parser_collection = $parsers;
	}

	public function analyze(ParsingContext $context): bool {
		$parsed = false;
		foreach ($this->_parser_collection as $parser) {
			foreach ($context as $symbol) {
				$parsed = $parser->parse($context) || $parsed;
			}
		}
		return $parsed;
	}
}