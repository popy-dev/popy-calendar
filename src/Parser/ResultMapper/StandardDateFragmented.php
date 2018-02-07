<?php

namespace Popy\Calendar\Parser\ResultMapper;

use Popy\Calendar\ValueObject\DateParts;
use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Maps standard format symbols to DateFragmentedRepresentationInterface fields.
 */
class StandardDateFragmented implements ResultMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date = null)
    {
        if (!$data instanceof DateFragmentedRepresentationInterface) {
            return;
        }

        $dateParts = $date->getDateParts() ?: new DateParts();

        $dateParts
            ->withFragments([
                $this->determineMonth($parts),
                $this->determineDay($parts),
            ])
        ;

        return $date->withDateParts($dateParts);
    }

    /**
     * Determine month (0 indexed).
     *
     * @param DateLexerResult $parts
     *
     * @return integer
     */
    protected function determineMonth(DateLexerResult $parts)
    {
        // m   Numeric representation of a month, with leading zeros   01 through 12
        // n   Numeric representation of a month, without leading zeros
        // F   A full textual representation of a month, such as January or March
        // M   A short textual representation of a month, three letters
        if (null !== $m = $parts->getFirst('m', 'n', 'F', 'M')) {
            return (int)$m - 1;
        }
    }

    /**
     * Determine day (0 indexed).
     *
     * @param DateLexerResult $parts
     * 
     * @return integer|null
     */
    protected function determineDay(DateLexerResult $parts)
    {
        // d   Day of the month, 2 digits with leading zeros   01 to 31
        // j   Day of the month without leading zeros  1 to 31
        if (null !== $d = $parts->getFirst('j', 'd')) {
            return (int)$d - 1;
        }
    }

    /**
     * Determine day of week (0 indexed)
     *
     * @param DateLexerResult $parts
     * 
     * @return integer|null
     */
    protected function determineDayOfWeek(DateLexerResult $parts)
    {
        // w   Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
        // D   A textual representation of a day, three letters
        // l (lowercase 'L')   A full textual representation of the day of the week
        if (null !== $w = $parts->getFirst('w', 'D', 'l')) {
            return (int)$w;
        }

        // N   ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 1 (for Monday) through 7 (for Sunday)
        if (null !== $N = $parts->get('N')) {
            return (int)$N - 1;
        }
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

        // W   ISO-8601 week number of year, weeks starting on Monday
        $w = $parts->get('W');
        if (null !== $w && null !== $dow = $this->determineDayOfWeek($parts)) {
            return $parts->get('W') * 10 + $dow;
        }

        if (
            (null !== $m = $this->determineMonth($parts))
            && null !== $d = $this->determineDay($parts)
        ) {
            return $m * 30 + $d;
        }
    }
}
