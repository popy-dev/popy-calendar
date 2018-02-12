<?php

namespace Popy\Calendar\Formatter\SymbolFormatter;

use Popy\Calendar\FormatterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formatter\SymbolFormatterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Handles Standard format recursive symbols.
 */
class StandardRecursive implements SymbolFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormatterInterface $formatter)
    {
        if ($token->is('c')) {
            // c   ISO 8601 date (added in PHP 5)  2004-02-12T15:19:21+00:00
            return $formatter->formatDateRepresentation($input, 'Y-m-d\\TH:i:sP');
        }

        if ($token->is('r')) {
            // r   » RFC 2822 formatted date   Example: Thu, 21 Dec 2000 16:01:07 +0200
            return $formatter->formatDateRepresentation($input, 'D, d M Y H:i:s P');
        }
    }
}
