<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Parser\DateLexer\PregChoice;

/**
 * Implementation of the native DateTime formats using preg lexers.
 */
class PregNativeDateTime implements SymbolParserInterface
{
    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $parser)
    {
        // Todo : Make it a PregChoice
        if ($token->isOne('a', 'A')) {
            // a   Lowercase Ante meridiem and Post meridiem   am or pm
            // A   Uppercase Ante meridiem and Post meridiem   AM or PM
            return new PregSimple($token, '[apAP][mM]');
        }

        if ($token->is('B')) {
            // B   Swatch Internet time    000 through 999
            return new PregSimple($token, '\d\d\d');
        }

        if ($token->isOne('g', 'G')) {
            // g   12-hour format of an hour without leading zeros 1 through 12
            // G   24-hour format of an hour without leading zeros 0 through 23
            return new PregSimple($token, '\d\d');
        }

        if ($token->isOne('h', 'H')) {
            // h   12-hour format of an hour with leading zeros    01 through 12
            // H   24-hour format of an hour with leading zeros    00 through 23
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('i')) {
            // i   Minutes with leading zeros  00 to 59
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('s')) {
            // s   Seconds, with leading zeros 00 through 59
            return new PregSimple($token, '\d\d');
        }

        if ($token->is('u')) {
            // u   Microseconds
            return new PregSimple($token, '\d{6}');
        }

        if ($token->is('v')) {
            // u   Milliseconds
            return new PregSimple($token, '\d\d\d');
        }

        if ($token->is('µ')) {
            // Remaining microseconds
            return new PregSimple($token, '\d\d\d');
        }
    }
}
