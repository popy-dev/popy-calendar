<?php

namespace Popy\Calendar\Parser\ResultMapper;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Maps standard format symbols to DateRepresentationInterface fields.
 */
class Date implements ResultMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date = null)
    {
        if (null === $data) {
            return;
        }

        return $data
            // SI Units
            // U   Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
            // u   Microseconds
            ->withUnixTime($parts->get('U'))
            ->withUnixMicroTime($parts->get('u'))
        ;
    }
}
