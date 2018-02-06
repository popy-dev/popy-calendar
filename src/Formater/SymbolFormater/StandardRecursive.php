<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Handles Standard format recursive symbols.
 */
class StandardRecursive implements SymbolFormaterInterface
{
    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
    {
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
