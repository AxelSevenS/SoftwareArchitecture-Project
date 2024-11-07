<?php
declare(strict_types=1);

namespace App;

use App\Calculator\SymbolsCalculator;
use App\Calculator\Parsing\Parsers\AggregateParser;
use App\Calculator\Parsing\Parsers\ParserCollection;
use App\Calculator\Parsing\Parsers\OperatorParser;
use App\Calculator\Analysis\SyntaxAnalyzer;
use App\Calculator\Analysis\Analyzers\ParenthesesAnalyzer;

class Kernel {
	private SyntaxAnalyzer $_analyzer;

	public function __construct() {
		$this->_analyzer = new ParenthesesAnalyzer(new ParserCollection([
			new OperatorParser(
				['^', '**'],
				function(float $a, float $b): float { return $a ** $b; }
			),

			new AggregateParser([
				new OperatorParser(
					'*',
					function(float $a, float $b): float { return $a * $b; }
				),
				new OperatorParser(
					'/',
					function(float $a, float $b): float { return $a / $b; }
				),
				new OperatorParser(
					'%',
					function(float $a, float $b): float { return $a % $b; }
				),
			]),

			new AggregateParser([
				new OperatorParser(
					'+',
					function(float $a, float $b): float { return $a + $b; }
				),
				new OperatorParser(
					'-',
					function(float $a, float $b): float { return $a - $b; }
				),
			])
		]));
	}

	public function run(array& $strings): void {
		if (!is_array($strings)) {
			return;
		}

		$calc = new SymbolsCalculator($this->_analyzer);

		echo $calc->calculate($strings);
	}

	public function __invoke(array& $strings): void {
		$this->run($strings);
	}
}