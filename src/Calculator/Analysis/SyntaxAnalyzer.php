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


	private function tokenize_symbols(array& $symbols, array& $tokens): array {
		usort($tokens, fn($a, $b) => strlen($b) - strlen($a));
		$tokens_pattern = '~(' . implode('|', array_map('preg_quote', $tokens)) . ')~';

		// Tokenize all symbols
		return array_reduce($symbols, function ($tokenized_symbols, $symbol) use ($tokens_pattern) {

			// Tokenize a single symbol
			$tokenized_symbol = array_reduce([$symbol], function ($sanitized_symbol, $symbol) use ($tokens_pattern) {
				$split_symbol = preg_split(
					$tokens_pattern,
					$symbol,
					-1,
					PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
				);

				$split_symbol = array_map('trim', $split_symbol);
				$split_symbol = array_filter($split_symbol, fn($value) => $value !== '');

				return array_merge($sanitized_symbol, $split_symbol);
			}, []);

			return array_merge($tokenized_symbols, $tokenized_symbol);
		}, []);
	}

	abstract protected function _get_tokens(): array;
}