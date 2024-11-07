<?php
declare(strict_types=1);

namespace App\Calculator\Analysis;

use App\Calculator\Parsing\ParsingContext;

interface SyntaxAnalyzer {
	function analyze(ParsingContext $context): bool;
}