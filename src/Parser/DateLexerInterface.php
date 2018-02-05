<?php

namespace Popy\Calendar\Parser;

/**
 * Extracts date components from the input date string, starting at given offset
 *
 * DateLexerInterfaces usualy are produced by FormatParserInterfaces.
 */
interface DateLexerInterface
{
    /**
     * Tries to find a date representation in input string, then return it as an
     * DateLexerResult instance, containing the offset where the lexer stopped
     * and every extracted information.
     *
     * A possible evolution would be that DateLexerInterface returns an iterable
     * of results, for cases where a single lexer could match different strings,
     * in order to try a full match with every result, just like regular
     * expression works.
     *
     * @param string $string
     *
     * @return DateLexerResult|null
     */
    public function tokenizeDate($string, $offset = 0);
}
