<?php

declare(strict_types=1);

namespace App\Calculator\Parsing;

use App\Calculator\Parsing\ParsingContext;

abstract class Parser {
	public abstract function parse(ParsingContext $context): bool;
}