<?php
declare(strict_types=1);

namespace App\Calculator\Analysis;

use App\Calculator\Parsing\Parsers\ParserCollection;
use App\Calculator\Parsing\ParsingContext;

final class ParenthesesData {
	private int $_start_index;
	private int $_end_index;

	private array $_children = [];


	public function __construct(ParsingContext $context) {
		$index = $context->key();
		$this->_start_index = $index;
		$context->remove($index);

		while ($context->valid()) {
			$context->next();
			$operation = $context->current();

			if (is_string($operation)) {
				if ($operation === '(') {
					$this->_children[] = new ParenthesesData($context);
				} else if ($operation === ')') {
					$index = $context->key();
					$this->_end_index = $index;
					break;
				}
			}
		}

		if (!isset($this->_end_index)) {
			throw new \Exception('Unbalanced parentheses');
		}
		$context->remove($this->_end_index);
	}


	public function parse(ParsingContext $context, ParserCollection $parser_collection): bool {
		$parsed = false;
		foreach ($this->_children as $child) {
			$parsed = $child->parse($context, $parser_collection) || $parsed;
		}

		$context->move_to($this->_start_index + 1);
		while ($context->key() < $this->_end_index) {
			foreach ($parser_collection as $parser) {
				$parsed = $parser->parse($context) || $parsed;
			}
			$context->next();
		}

		return $parsed;
	}
}