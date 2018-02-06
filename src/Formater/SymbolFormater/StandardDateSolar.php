<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Standard format, handling DateSolarRepresentationInterface.
 *
 * o format isn't properly handled for now
 */
class StandardDateSolar implements SymbolFormaterInterface
{
    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
    {
        if (!$input instanceof DateSolarRepresentationInterface) {
            return;
        }
        if ($token->is('y')) {
            // y   A two digit representation of a year
            return sprintf('%02d', $input->getYear() % 100);
        }

        if ($token->is('Y') || $token->is('o')) {
            // Y   A full numeric representation of a year, 4 digits
            // o   ISO-8601 week-numbering year. This has the same value as Y, except that if the ISO week number (W) belongs to the previous or next year, that year is used instead.
            return sprintf('%04d', $input->getYear());
        }

        if ($token->is('L')) {
            // L   Whether it's a leap year
            return (string)$input->isLeapYear();
        }

        if ($token->is('z')) {
            // z   The day of the year (starting from 0)
            return (string)$input->getDayIndex();
        }
    }
}
