<?php

namespace Popy\Calendar\Parser;

/**
 * Tokenizes a string format, flagging escaped characters as litterals.
 */
interface FormatLexerInterface
{
    /**
     * Tokenize input string format.
     *
     * @param string $format
     *
     * @return array<FormatToken>
     */
    public function tokenize($format);
}