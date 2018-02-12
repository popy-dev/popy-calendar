<?php

namespace Popy\Calendar\Formatter\SymbolFormatter;

use Popy\Calendar\FormatterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formatter\SymbolFormatterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Standard format, handling DateRepresentationInterface.
 */
class StandardDate implements SymbolFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormatterInterface $formatter)
    {
        if ($token->is('U')) {
            // U   Seconds since the Unix Epoch (January 1 1970 00:00:00 GMT)
            return $input->getUnixTime();
        }

        if ($token->is('u')) {
            // u   Microseconds
            return sprintf('%06d', $input->getUnixMicroTime());
        }

        if ($token->is('e')) {
            // e   Timezone identifier
            return $input->getTimezone()->getName();
        }

        if ($token->is('I')) {
            // I (capital i) Whether or not the date is in daylight saving time
            return (int)$input->getOffset()->isDst();
        }

        if ($token->isOne('O', 'P')) {
            // O   Difference to Greenwich time (GMT) in hours
            // P   Difference to Greenwich time (GMT) with colon
            $f = '%s%02d%02d';
            if ($token->is('P')) {
                $f = '%s%02d:%02d';
            }

            $value = intval($input->getOffset()->getValue() / 60);

            return sprintf(
                $f,
                $value < 0 ? '-' : '+',
                intval(abs($value) / 60),
                intval(abs($value) % 60)
            );
        }

        if ($token->is('T')) {
            // T   Timezone abbreviation
            return $input->getOffset()->getAbbreviation();
        }

        if ($token->is('Z')) {
            // Z   Timezone offset in seconds.
            return (int)$input->getOffset()->getValue();
        }
    }
}
