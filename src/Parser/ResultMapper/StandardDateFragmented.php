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
    public function map(DateLexerResult $parts, DateRepresentationInterface $date)
    {
        if (!$date instanceof DateFragmentedRepresentationInterface) {
            return;
        }

        $dateParts = $date
            ->getDateParts()
            ->withFragments([
                $this->determineMonth($parts),
                $this->determineDay($parts),
            ])
            ->withTransversals([
                $parts->get('o') === null ? null : (int)$parts->get('o'),
                $this->determineWeekIndex($parts),
                $this->determineDayOfWeek($parts),
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
     * Determine day of week (0 indexed, starts by monday)
     *
     * @param DateLexerResult $parts
     * 
     * @return integer|null
     */
    protected function determineDayOfWeek(DateLexerResult $parts)
    {
        // D   A textual representation of a day, three letters
        // l (lowercase 'L')   A full textual representation of the day of the week
        if (null !== $w = $parts->getFirst('D', 'l')) {
            return (int)$w;
        }

        // N   ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 1 (for Monday) through 7 (for Sunday)
        if (null !== $w = $parts->get('N')) {
            return (int)$w - 1;
        }

        // w   Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
        if (null !== $w = $parts->get('w')) {
            return ((int)$w + 6) % 7;
        }
    }

    /**
     * Determine ISO week index from iso week number.
     *
     * @param DateLexerResult $parts
     *
     * @return integer|null
     */
    protected function determineWeekIndex(DateLexerResult $parts)
    {
        if (null === $w = $parts->get('W')) {
            return;
        }

        return intval($w) - 1;
    }
}
