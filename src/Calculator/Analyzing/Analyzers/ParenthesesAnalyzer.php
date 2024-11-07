<?php
declare(strict_types=1);

namespace App\Calculator\Analyzing\Analyzers;

use App\Calculator\Parsing\Parsers\ParserCollection;
use App\Calculator\Parsing\ParsingContext;
use App\Calculator\Analyzing\SyntaxAnalyzer;

class ParenthesesData {
	public int $start_index;
	public int $end_index;

	public array $children = [];


	public function __construct(ParsingContext $context) {
		$index = $context->key();
		$this->start_index = $index;
		$context->remove($index);

		while ($context->valid()) {
			$context->next();
			$operation = $context->current();

			if (is_string($operation)) {
				if ($operation === '(') {
					$this->children[] = new ParenthesesData($context);
				} else if ($operation === ')') {
					$index = $context->key();
					$this->end_index = $index;
					break;
				}
			}
		}

		if (!isset($this->end_index)) {
			throw new \Exception('Unbalanced parentheses');
		}
		$context->remove($this->end_index);
	}


	public function parse(ParsingContext $context, ParserCollection $parser_collection): bool {
		$context->move_to($this->start_index + 1);

		$parsed = false;
		foreach ($this->children as $child) {
			$parsed = $child->parse($context, $parser_collection) || $parsed;
		}
		while ($context->valid()) {
			foreach ($parser_collection as $parser) {
				$parsed = $parser->parse($context) || $parsed;
			}
			$context->next();
		}

		return $parsed;
	}
}

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