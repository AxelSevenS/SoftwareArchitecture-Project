<?php

declare(strict_types=1);

namespace App;

use App\Calculator\SymbolsCalculator;
use App\Calculator\Parsing\Parsers\NumberParser;
use App\Calculator\Parsing\Parsers\OperatorParser;
use App\Calculator\Parsing\Parsers\AggregateParser;

class Kernel {

	private $_numberParser;
	private $_exponentMultiplicationDivisionModulusParsers;
	private $_additionSubtractionParsers;

	public function __construct() {
		$this->_numberParser = new NumberParser();

		$this->_exponentMultiplicationDivisionModulusParsers = new AggregateParser([
			new OperatorParser('^', function(float $a, float $b): float { return $a ** $b; }),
			new OperatorParser('*', function(float $a, float $b): float { return $a * $b; }),
			new OperatorParser('/', function(float $a, float $b): float { return $a / $b; }),
			new OperatorParser('%', function(float $a, float $b): float { return $a % $b; }),
		]);

		$this->_additionSubtractionParsers = new AggregateParser([
			new OperatorParser('+', function(float $a, float $b): float { return $a + $b; }),
			new OperatorParser('-', function(float $a, float $b): float { return $a - $b; }),
		]);
	}

	public function run(array& $strings): void {
		if (!is_array($strings)) return;

		$calc = new SymbolsCalculator([
			$this->_numberParser,
			$this->_exponentMultiplicationDivisionModulusParsers,
			$this->_additionSubtractionParsers,
		]);
		$result = $calc->calculate($strings);
		echo $result;
	}

	public function __invoke(array& $strings): void {
		$this->run($strings);
	}
}