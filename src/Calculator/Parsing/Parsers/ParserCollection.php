<?php
declare(strict_types=1);

namespace App\Calculator\Parsing\Parsers;

use Iterator;
use ArrayAccess;
use App\Calculator\Parsing\SymbolParser;
use App\Calculator\Parsing\ParsingContext;

class ParserCollection implements SymbolParser, Iterator, ArrayAccess {
	private array $_parsers;
	private int $_position = 0;

	public function __construct(SymbolParser|array $parsers) {
		if (!is_array($parsers)) {
			$parsers = [$parsers];
		}
		$this->_parsers = $parsers;
	}


	// Iterator methods
	public function current(): mixed {
		return $this->_parsers[$this->_position];
	}

	public function key(): mixed {
		return $this->_position;
	}

	public function next(): void {
		++$this->_position;
	}

	public function rewind(): void {
		$this->_position = 0;
	}

	public function valid(): bool {
		return isset($this->_parsers[$this->_position]);
	}


	// ArrayAccess methods
	public function offsetExists(mixed $offset): bool {
		return isset($this->_parsers[$offset]);
	}

	public function offsetGet(mixed $offset): mixed {
		return $this->_parsers[$offset];
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		if (is_null($offset)) {
			$this->_parsers[] = $value;
		} else {
			$this->_parsers[$offset] = $value;
		}
	}

	public function offsetUnset(mixed $offset): void {
		unset($this->_parsers[$offset]);
	}


	public function parse(ParsingContext $context): bool {
		$parsed = false;
		foreach ($this->_parsers as $parser) {
			$parsed = $parser->parse($context) || $parsed;
		}
		return $parsed;
	}
}