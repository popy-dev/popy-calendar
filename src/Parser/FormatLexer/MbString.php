<?php

namespace Popy\Calendar\Parser\FormatLexer;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\FormatLexerInterface;

/**
 * mb_string based implementation.
 */
class MbString implements FormatLexerInterface
{
    /**
     * @inheritDoc
     */
    public function tokenizeFormat($format)
    {
        $res = [];
        $escaped = false;
        $length = mb_strlen($format);

        for ($i=0; $i < $length; $i++) {
            $symbol = mb_substr($format, $i, 1);

            if ($escaped) {
                $escaped = false;
                $res[] = new FormatToken($symbol, FormatToken::TYPE_LITTERAL);
                continue;
            }

            if ($symbol === '\\') {
                $escaped = true;
                continue;
            }

            $res[] = new FormatToken($symbol, FormatToken::TYPE_SYMBOL);
        }

        $res[] = new FormatToken(null, FormatToken::TYPE_EOF);

        return $res;
    }
}
