<?php
declare(strict_types=1);

namespace App\Calculator;

use App\Calculator\Analysis\SyntaxAnalyzer;
use App\Calculator\Parsing\ParsingContext;

class SymbolsCalculator {
	public function __construct(
		private SyntaxAnalyzer $_analyzer
	) { }

	public function calculate(array& $symbols): string {
		$symbols = $this->_analyzer->sanitize_symbols($symbols);

		$parsing_context = new ParsingContext($symbols);

		try {
			$this->_analyzer->analyze($parsing_context);

			return (string) $parsing_context->result();
		} catch (\Exception $e) {
			echo $e->getMessage() . /* ' :: ' . join(' ', $parsing_context->values()) .  */"\n";
			return join(' ', $symbols);
		}
	}
}