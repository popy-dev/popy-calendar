<?php

namespace Popy\Calendar\Formatter\SymbolFormatter;

use Popy\Calendar\FormatterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formatter\LocalisationInterface;
use Popy\Calendar\Formatter\SymbolFormatterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Standard format, handling DateFragmentedRepresentationInterface.
 *
 * Weeks and day names assume a gregorian calendar structure.
 */
class StandardDateFragmented implements SymbolFormatterInterface
{
    /**
     * Locale (used for day & month names)
     *
     * @var LocalisationInterface
     */
    protected $locale;

    /**
     * Class constructor.
     *
     * @param LocalisationInterface $locale
     */
    public function __construct(LocalisationInterface $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormatterInterface $formatter)
    {
        if (!$input instanceof DateFragmentedRepresentationInterface) {
            return;
        }

        if ($token->is('F')) {
            // F   A full textual representation of a month
            return (string)$this->locale->getMonthName($input->getDateParts()->get(0));
        }

        if ($token->is('M')) {
            // M   A short textual representation of a month, three letters
            return (string)$this->locale->getMonthShortName($input->getDateParts()->get(0));
        }

        if ($token->is('m')) {
            // m   Numeric representation of a month, with leading zeros
            return sprintf('%02d', $input->getDateParts()->get(0) + 1);
        }

        if ($token->is('n')) {
            // n   Numeric representation of a month, without leading zeros
            return $input->getDateParts()->get(0) + 1;
        }

        if ($token->is('t')) {
            // t    Number of days in the given month
            return (int)$input->getDateParts()->getSize(0);
        }

        if ($token->is('d')) {
            // d   Day of the month, 2 digits with leading zeros
            return sprintf('%02d', $input->getDateParts()->get(1) + 1);
        }

        if ($token->is('j')) {
            // j   Day of the month without leading zeros
            return $input->getDateParts()->get(1) + 1;
        }

        if ($token->is('S')) {
            // S   English ordinal suffix for the day of the month, 2 characters
            return (string)$this->locale->getNumberOrdinalSuffix($input->getDateParts()->get(1));
        }

        if ($token->is('l')) {
            // l (lowercase 'L')   A full textual representation of the day of the week
            return (string)$this->locale->getDayName($input->getDateParts()->getTransversal(2));
        }

        if ($token->is('D')) {
            // D   A textual representation of a day, three letters
            return (string)$this->locale->getDayShortName($input->getDateParts()->getTransversal(2));
        }
        
        if ($token->is('w')) {
            // w   Numeric representation of the day of the week   0 (for Sunday) through 6 (for Saturday)
            return (1 + $input->getDateParts()->getTransversal(2)) % 7;
        }

        if ($token->is('N')) {
            // N   ISO-8601 numeric representation of the day of the week (added in PHP 5.1.0) 1 (for Monday) through 7 (for Sunday)
            return $input->getDateParts()->getTransversal(2) + 1;
        }

        if ($token->is('W')) {
            // W   ISO-8601 week number of year, weeks starting on Monday
            return sprintf(
                '%02d',
                $input->getDateParts()->getTransversal(1) + 1
            );
        }

        if ($token->is('o')) {
            // Y   A full numeric representation of a year, 4 digits
            // o   ISO-8601 week-numbering year. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
            return sprintf('%04d', $input->getDateParts()->getTransversal(0));
        }
    }

    protected function getIsoDayOfWeek(DateFragmentedRepresentationInterface $input)
    {
        return $this->getIsoDayOfWeekFromIndex($input->getEraDayIndex());
    }

    protected function getIsoWeekNumber(DateFragmentedRepresentationInterface $input)
    {
        $fixedDayIndex = $this->getFixedIsoDayIndex($input);

        if ($fixedDayIndex < 0) {
            return 52;
        }

        return intval($fixedDayIndex / 7) + 1;
    }

    protected function getIsoYearNumber(DateFragmentedRepresentationInterface $input)
    {
        $fixedDayIndex = $this->getFixedIsoDayIndex($input);

        if ($fixedDayIndex < 0) {
            return $input->getYear() - 1;
        }

        return $input->getYear();
    }

    protected function getFixedIsoDayIndex(DateFragmentedRepresentationInterface $input)
    {
        $firstWeekStart = $this->getIsoDayOfWeekFromIndex($input->getEraDayIndex() - $input->getDayIndex()) - 1;

        if ($firstWeekStart > 3) {
            $firstWeekStart -= 7;
        }

        return $input->getDayIndex() + $firstWeekStart;
    }


    protected function getIsoDayOfWeekFromIndex($index)
    {
        // Assuming the era starting year is 1970, it starts a Thursday.
        $index += 3;

        return $index % 7 + 1;
    }
}
