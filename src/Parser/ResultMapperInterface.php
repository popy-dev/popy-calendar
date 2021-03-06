<?php

namespace Popy\Calendar\Parser;

use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Maps date lexer results into a date representation.
 */
interface ResultMapperInterface
{
    /**
     * Maps lexer result parts into a DateRepresentation
     *
     * @param DateLexerResult             $parts Result parts.
     * @param DateRepresentationInterface $date  Date built by previous mappers.
     *
     * @return DateRepresentationInterface|null
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date);
}
