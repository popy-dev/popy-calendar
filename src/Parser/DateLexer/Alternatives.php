<?php

namespace Popy\Calendar\Parser\DateLexer;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\DateLexerInterface;

/**
 * Alternatives implementation : if any internal lexer matches, its result will
 * be returned.
 */
class Alternatives implements DateLexerInterface
{
    /**
     * Lexer list.
     *
     * @var array
     */
    protected $lexers = [];

    /**
     * Add a lexer.
     *
     * @param DateLexerInterface $lexer
     */
    public function addLexer(DateLexerInterface $lexer)
    {
        $this->lexers[] = $lexer;
    }

    /**
     * @inheritDoc
     */
    public function tokenizeDate($string, $offset = 0)
    {
        foreach ($this->lexers as $lexer) {
            if (null !== $res = $lexer->tokenizeDate($string, $offset)) {
                // A lexer failed to match : exit.
                return $res;
            }
        }
    }
}
