<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;

/**
 * Standard format, handling DateTimeRepresentationInterface.
 *
 * Not (yet) handling half-time format, so every time is shown as AM.
 */
class StandardDateTime implements SymbolFormaterInterface
{
    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
    {
        if (!$input instanceof DateTimeRepresentationInterface) {
            return;
        }

        if ($token->is('a', 'A')) {
            // a   Lowercase Ante meridiem and Post meridiem   am or pm
            // A   Uppercase Ante meridiem and Post meridiem   AM or PM
            return $token->is('a') ? 'am' : 'AM';
        }

        if ($token->is('B')) {
            // B   Swatch Internet time    000 through 999
            return sprintf('%03d', intval($input->getTime()->getRatio() / 1000));
        }

        if ($token->is('g', 'G')) {
            // g   12-hour format of an hour without leading zeros 1 through 12
            // G   24-hour format of an hour without leading zeros 0 through 23
            return $input->getTime()->get(0);
        }

        if ($token->is('h', 'H')) {
            // h   12-hour format of an hour with leading zeros    01 through 12
            // H   24-hour format of an hour with leading zeros    00 through 23
            return sprintf('%02d', $input->getTime()->get(0));
        }

        if ($token->is('i')) {
            // i   Minutes with leading zeros  00 to 59
            return sprintf('%02d', $input->getTime()->get(1));
        }

        if ($token->is('s')) {
            // s   Seconds, with leading zeros 00 through 59
            return sprintf('%02d', $input->getTime()->get(2));
        }

        if ($token->is('v')) {
            // v   Milliseconds
            return sprintf('%03d', intval($input->getTime()->get(3)));
        }

        if ($token->is('µ')) {
            // v   Milliseconds
            return sprintf('%03d', intval($input->getTime()->get(4)));
        }
    }
}
