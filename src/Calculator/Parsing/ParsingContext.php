<?php
declare(strict_types=1);

namespace App\Calculator\Parsing;

use Iterator;
use ArrayAccess;

class ParsingContext implements Iterator, ArrayAccess {
	private int $_index = 0;
	private array $_symbols; // float|string

	public function __construct(array $symbols) {
		$this->_symbols = $symbols;

		foreach ($this->_symbols as &$symbol) {
			if (is_numeric($symbol)) {
				$symbol = (float) $symbol;
			}
		}

		$this->rewind();
	}


	public function offsetExists(mixed $offset): bool {
		return isset($this->_symbols[$this->_index + $offset]);
	}

	public function offsetGet(mixed $offset): float|string|null {
		return $this->_symbols[$this->_index + $offset] ?? null;
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		if (is_null($offset)) {
			$this->_symbols[] = $value;
		} else if (is_float($offset) || is_string($offset)) {
			$this->_symbols[$this->_index + $offset] = $value;
		}
	}

	public function offsetUnset(mixed $offset): void {
		unset($this->_symbols[$this->_index + $offset]);
	}


	public function current(): float|string|null {
		return $this[0];
	}

	public function key(): int {
		return $this->_index;
	}

	public function values(): array {
		return $this->_symbols;
	}

	public function next(): void {
		$this->_index++;
	}

	public function rewind(): void {
		$this->_index = 0;
	}

	public function move_to(int $index): void {
		$this->_index = $index;
	}

	public function valid(): bool {
		return isset($this->_symbols[$this->_index]);
	}

	public function remove(int $index): void {
		array_splice($this->_symbols, $index, 1);
		if ($index <= $this->_index) {
			$this->_index--;
		}
	}

	public function collapse_result(
		float|string $result,
		int $negative_offset = 0,
		int $positive_offset = 0
	): void {
		$offset = max($this->_index - $negative_offset, 0);
		$length = $negative_offset + $positive_offset + 1;

		array_splice($this->_symbols, $offset, $length, $result);
		$this->_index = $offset;
	}

	public function result(): float {
		if (count($this->_symbols) !== 1) {
			throw new \Exception("Could not Parse");
		}
		return $this->_symbols[0];
	}
}