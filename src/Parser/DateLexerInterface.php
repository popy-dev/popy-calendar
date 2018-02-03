<?php

namespace Popy\Calendar\Parser;

/**
 * Extracts date components from the input date string.
 *
 * DateLexerInterfaces usualy are produced by FormatParserInterfaces.
 */
interface DateLexerInterface
{
    /**
     * Tries to find a date representation in input string, then return it as an
     * array of component => value, or null if nothing found.
     *
     * Exemple of result : [Y=>1970, m=>01, d=>01]
     * 
     * @param string $string
     *
     * @return array|null
     */
    public function tokenizeDate($string);
}
