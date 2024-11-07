<?php
declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\SymbolParser;
use App\Calculator\Parsing\ParsingContext;

class AggregateParser implements SymbolParser {
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

	public function get_tokens(): array {
		return array_reduce($this->_parsers, function($acc, $parser) {
			return array_merge($acc, $parser->get_tokens());
		}, []);
	}
}