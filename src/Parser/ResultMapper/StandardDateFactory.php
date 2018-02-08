<?php

namespace Popy\Calendar\Parser\ResultMapper;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Standard;

/**
 * Builds a standard gregorian date representation (to initialize a mapper chain)
 */
class StandardDateFactory implements ResultMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date = null)
    {
        return new Standard();
    }
}
