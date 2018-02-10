<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;

/**
 * Implementation of the native DateTime recursive formats using preg lexers.
 */
class PregNativeRecursive implements SymbolParserInterface
{
    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $parser)
    {
        if ($token->is('c')) {
            // c  ISO 8601 date (added in PHP 5)  2004-02-12T15:19:21+00:00
            return $parser->parseFormat('Y-m-d\TH:i:sP');
        }

        if ($token->is('r')) {
            // r  RFC 2822 formatted date   Example: Thu, 21 Dec 2000 16:01:07 +0200
            return $parser->parseFormat('D, d M Y H:i:s P');
        }
    }
}
