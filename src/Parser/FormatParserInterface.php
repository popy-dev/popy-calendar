<?php

namespace Popy\Calendar\Parser;

/**
 * Parses a format string (usually using a FormatLexerInterface) then use
 * it to build a DateLexerInterface object.
 */
interface FormatParserInterface
{
    /**
     * Parse input format.
     *
     * @param string  $format
     * @param boolean $isRecursiveCall
     *
     * @return DateLexerInterface
     */
    public function parseFormat($format, $isRecursiveCall = false);
}
