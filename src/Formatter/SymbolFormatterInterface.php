<?php

namespace Popy\Calendar\Formatter;

use Popy\Calendar\FormatterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Formats a date according to a singne format token/symbol.
 */
interface SymbolFormatterInterface
{
    /**
     * Formats a date representation according to input $token. Will return null
     * if the input token isn't handled.
     *
     * @param DateRepresentationInterface $input    Input date.
     * @param FormatToken                 $token    Symbol/Token.
     * @param FormatterInterface           $formatter Date Formatter (can be used for recursive calls).
     *
     * @return string|null
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormatterInterface $formatter);
}
