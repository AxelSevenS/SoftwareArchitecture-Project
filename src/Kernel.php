<?php

declare(strict_types=1);

namespace App;

use App\Calculator\Parsing\Parsers\AggregateParser;
use App\Calculator\SymbolsCalculator;
use App\Calculator\Parsing\Parsers\OperatorParser;
use App\Calculator\Parsing\Parsers\MultiParser;

class Kernel {
	private $_parser;

	public function __construct() {
		$this->_parser = new MultiParser([
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
		]);
	}

	public function run(array& $strings): void {
		if (!is_array($strings)) {
			return;
		}

		$calc = new SymbolsCalculator($this->_parser);
		$result = $calc->calculate($strings);
		echo $result;
	}

	public function __invoke(array& $strings): void {
		$this->run($strings);
	}
}