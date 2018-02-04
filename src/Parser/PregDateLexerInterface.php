<?php

namespace Popy\Calendar\Parser;

/**
 * PREG based DateLexer.
 */
interface PregDateLexerInterface extends DateLexerInterface
{
    /**
     * Get expression (non delimited/anchored) to be inserted in a bigger
     * expression.
     *
     * @return string
     */
    public function getExpression();

    /**
     * Hydrate resultset (as another lexer will match, it will have to delegate
     * hydratation to sub-lexers)
     *
     * @param DateLexerResult  $result Result to hydrate.
     * @param array            $match  preg matches array.
     * @param integer          $offset Offset in matches.
     *
     * @return integer The new offset.
     */
    public function hydrateResult(DateLexerResult $result, $match, $offset = 1);
}
