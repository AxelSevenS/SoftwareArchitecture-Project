<?php

declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\Parser;
use App\Calculator\Parsing\ParsingContext;

class OperatorParser extends Parser {
	private string $_symbol;
	private $_callback;

	public function __construct(string $symbol, callable $callback) {
		$this->_symbol = $symbol;
		$this->_callback = $callback;
	}

	public function parse(ParsingContext $context): bool {
		// if the current symbol is an operation, we need to collapse it as an operation
		$operation = $context->current();
		$previous = $context[-1] ?? 0;
		$next = $context[1] ?? 0;
		$result = 0;
		if ($operation !== $this->_symbol) {
			return false;
		}
		$result = call_user_func($this->_callback, $previous, $next);
		$context->collapse_result($result, 1, 1);
		return true;
	}
}