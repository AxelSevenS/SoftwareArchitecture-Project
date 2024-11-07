<?php
declare(strict_types=1);

namespace App;

use App\Calculator\Parsing\Parsers\FunctionParser;
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
			new FunctionParser('cos', function(float $a): float { return cos($a); }, 1),
			new FunctionParser('sin', function(float $a): float { return sin($a); }, 1),

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
			]),
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