<?php
declare(strict_types=1);

namespace App\Calculator\Analysis;

use App\Calculator\Parsing\ParsingContext;
use App\Calculator\Parsing\Parsers\ParserCollection;

abstract class SyntaxAnalyzer {
	public function __construct(
		protected ParserCollection $_parser_collection
	) { }

	public function analyze(ParsingContext $context): bool {
		$parsed = false;
		foreach ($this->_parser_collection as $parser) {
			foreach ($context as $symbol) {
				$parsed = $parser->parse($context) || $parsed;
			}
		}
		return $parsed;
	}

	public final function sanitize_symbols(array& $symbols): array {
		$tokens = array_merge(
			$this->_parser_collection->get_tokens(),
			$this->_get_tokens()
		);

		return $this->tokenize_symbols($symbols, $tokens);
	}

	private function detach_symbols_by_token(array& $symbol_parts, string& $token): array {
		return array_reduce($symbol_parts, function ($detached_parts, $part) use ($token) {
			if (strpos($part, $token) !== false) {
				$escapedToken = preg_quote($token, '/');

				$split_parts = preg_split(
					"/($escapedToken)/",
					$part,
					-1,
					PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
				);
				$split_parts = array_map('trim', $split_parts);

				$split_parts = array_filter(
					$split_parts,
					fn($value) => !is_null($value) && $value !== ''
				);

				$detached_parts = array_merge($detached_parts, $split_parts);
			} else {
				$detached_parts[] = $part;
			}
			return $detached_parts;
		}, []);
	}


	private function tokenize_symbols(array& $symbols, array& $tokens): array {
		return array_reduce($symbols, function ($tokenized_symbols, $symbol) use ($tokens) {
			$symbol_parts = [$symbol];
			foreach ($tokens as $token) {
				$symbol_parts = $this->detach_symbols_by_token($symbol_parts, $token);
			}
			return array_merge($tokenized_symbols, $symbol_parts);
		}, []);
	}

	abstract protected function _get_tokens(): array;
}