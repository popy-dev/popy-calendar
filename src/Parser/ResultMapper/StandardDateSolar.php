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
    public function map(DateLexerResult $parts, DateRepresentationInterface $date)
    {
        if (!$date instanceof DateSolarRepresentationInterface) {
            return;
        }

        return $date
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
        // Assumes 'y' is properly handled in lexer
        return $parts->getFirst('Y', 'y');
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
        if (null !== $z = $parts->get('z')) {
            return (int)$z;
        }
    }
}
