<?php

namespace Popy\Calendar\Parser;

use Popy\Calendar\ValueObject\DateRepresentationInterface;

interface ResultMapperInterface
{
    /**
     * Maps lexer result parts into a DateRepresentation
     *
     * @param DateLexerResult                  $parts Result parts.
     * @param DateRepresentationInterface|null $date  Date built by previous mappers.
     *
     * @return DateRepresentationInterface|null
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date = null);
}
