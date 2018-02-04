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
     * @param string $string
     *
     * @return DateLexerResult|null
     */
    public function tokenizeDate($string, $offset = 0);
}
