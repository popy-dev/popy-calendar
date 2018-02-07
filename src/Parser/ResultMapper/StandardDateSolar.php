<?php

namespace Popy\Calendar\Parser\ResultMapper;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Maps standard format symbols to DateSolarRepresentationInterface fields.
 */
class StandardDateSolar implements ResultMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date = null)
    {
        if (!$data instanceof DateSolarRepresentationInterface) {
            return;
        }

        return $data
            ->withYear($this->determineYear($parts), $parts->get('L'))
            ->withDayIndex($this->determineDayIndex($parts), null)
        ;
    }

    /**
     * Determine year.
     *
     * @param DateLexerResult $parts
     *
     * @return integer|null
     */
    protected function determineYear(DateLexerResult $parts)
    {
        return $parts->getFirst('Y', 'o', 'y');
    }

    /**
     * Determine year.
     *
     * @param DateLexerResult $parts
     *
     * @return integer|null
     */
    protected function determineDayIndex(DateLexerResult $parts)
    {
        // z   The day of the year (starting from 0)
        // X   Day individual name
        if (null !== $z = $parts->getFirst('z', 'X')) {
            return (int)$z;
        }
    }
}
