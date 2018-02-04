<?php

namespace Popy\Calendar\Parser\DateLexer;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\PregDateLexerInterface;

/**
 * Abstract implementation of Preg based lexers.
 */
abstract class AbstractPreg implements PregDateLexerInterface
{
    /**
     * @inheritDoc
     */
    public function tokenizeDate($string, $offset = 0)
    {
        $match = [];

        if (!preg_match(
            '/\G'.$this->getExpression().'/',
            $string,
            $match,
            PREG_OFFSET_CAPTURE,
            $offset
        )) {
            return null;
        }

        $this->hydrateResult(
            $res = new DateLexerResult($offset + strlen($match[0][0])),
            $match
        );

        return $res;
    }
}
