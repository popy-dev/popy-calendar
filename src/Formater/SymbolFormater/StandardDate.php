<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Standard format, handling DateRepresentationInterface.
 */
class StandardDate implements SymbolFormaterInterface
{
    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
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

        if ($token->is('O', 'P')) {
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

        if ($token->is('c')) {
            // c   ISO 8601 date (added in PHP 5)  2004-02-12T15:19:21+00:00
            return $formater->formatDateRepresentation($input, 'Y-m-d\\TH:i:sP');
        }

        if ($token->is('r')) {
            // r   » RFC 2822 formatted date   Example: Thu, 21 Dec 2000 16:01:07 +0200
            return $formater->formatDateRepresentation($input, 'D, d M Y H:i:s P');
        }
    }
}
