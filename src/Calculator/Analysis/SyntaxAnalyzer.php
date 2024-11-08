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
		$escapedTokens = '~(' . implode('|', array_map('preg_quote', $tokens)) . ')~';

		return array_reduce(
			$symbols,
			function ($tokenized_symbols, $symbol) use ($escapedTokens) {
				return array_merge(
					$tokenized_symbols,
					array_reduce([$symbol], function ($detached_parts, $part) use ($escapedTokens) {

						$split_parts = preg_split(
							$escapedTokens,
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
						return $detached_parts;
					}, [])
				);
			}, []
		);
	}

	abstract protected function _get_tokens(): array;
}