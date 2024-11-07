<?php
declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use App\Calculator\Parsing\SymbolParser;
use App\Calculator\Parsing\ParsingContext;

class FunctionParser implements SymbolParser {
	private array $_symbols;
	private $_callback;
	private int $_param_count;

	public function __construct(array|string $symbols, callable $callback, int $param_count = 0) {
		$this->_symbols = is_array($symbols) ? $symbols : [$symbols];
		$this->_callback = $callback;
		$this->_param_count = $param_count;
	}

	public function parse(ParsingContext $context): bool {
		$operation = $context->current();

		if (!in_array($operation, $this->_symbols, true)) {
			return false;
		}

		$params = array_slice(
			$context->values(),
			$context->key() + 1,
			$this->_param_count
		);

		if (count($params) < $this->_param_count) {
			throw new \Exception('Invalid operation, missing function parameters');
		}

		$result = ($this->_callback)(...$params);
		$context->collapse_result($result, 0, $this->_param_count);
		return true;
	}

	public function get_tokens(): array {
		return $this->_symbols;
	}

	public function __toString(): string {
		return implode(', ', $this->_symbols);
	}
}