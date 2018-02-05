<?php

namespace Popy\Calendar\Parser;

/**
 * Parses an input FormatToken and returns a DateLexerInterface, or null if the
 * input token has to be considered as a litteral.
 */
interface SymbolParserInterface
{
    /**
     * Parse input symbol roken.
     *
     * @param FormatToken           $token
     * @param FormatParserInterface $formater
     *
     * @return DateLexerInterface|null
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $formater);
}
