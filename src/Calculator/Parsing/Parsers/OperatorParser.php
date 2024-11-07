<?php

declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\Parser;
use App\Calculator\Parsing\ParsingContext;

class OperatorParser extends Parser {
	private array $_symbols;
	private $_callback;

	public function __construct(array|string $symbols, callable $callback) {
		$this->_symbols = is_array($symbols) ? $symbols : [$symbols];
		$this->_callback = $callback;
	}

	public function parse(ParsingContext $context): bool {
		$operation = $context->current();

		if (!in_array($operation, $this->_symbols, true)) return false;

		$previous = $context[-1];
		$next = $context[1];

		if ($previous === null || $next === null) {
			throw new \Exception('Invalid operation, missing operands');
		}

		$result = call_user_func($this->_callback, $previous, $next);
		$context->collapse_result($result, 1, 1);
		return true;
	}

	public function __toString(): string {
		return implode(', ', $this->_symbols);
	}
}