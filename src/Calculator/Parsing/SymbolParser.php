<?php
declare(strict_types=1);

namespace App\Calculator\Parsing;

use App\Calculator\Parsing\ParsingContext;

interface SymbolParser {
	function parse(ParsingContext $context): bool;
	function get_tokens(): array;
}