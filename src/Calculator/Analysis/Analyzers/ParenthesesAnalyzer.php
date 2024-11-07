<?php
declare(strict_types=1);

namespace App\Calculator\Analysis\Analyzers;

use App\Calculator\Parsing\Parsers\ParserCollection;
use App\Calculator\Parsing\ParsingContext;
use App\Calculator\Analysis\SyntaxAnalyzer;
use App\Calculator\Analysis\ParenthesesData;

class ParenthesesAnalyzer implements SyntaxAnalyzer {
	public function __construct(
		private ParserCollection $_parser_collection
	) { }

	public function analyze(ParsingContext $context): bool {
		$parentheses_scopes = [];
		$context->rewind();

		while ($context->valid()) {
			$operation = $context->current();

			if (is_string($operation)) {
				if ($operation === '(') {
					$parentheses_scopes[] = new ParenthesesData($context);
				} else if ($operation === ')') {
					throw new \Exception('Unbalanced parentheses');
				}
			}

			$context->next();
		}


		$parsed = false;
		foreach ($parentheses_scopes as $parentheses_scope) {
			$parsed = $parentheses_scope->parse($context, $this->_parser_collection) || $parsed;
		}

		foreach ($this->_parser_collection as $parser) {
			foreach ($context as $symbol) {
				$parsed = $parser->parse($context) || $parsed;
			}
		}
		return $parsed;
	}
}