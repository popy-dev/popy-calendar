<?php

namespace Popy\Calendar\Parser\DateLexer;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\DateLexerInterface;

/**
 * Collection/Chain implementation : every contained lexer has to match its own
 * part, then the full result is returned.
 */
class Collection implements DateLexerInterface
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
        $result = new DateLexerResult($offset);

        foreach ($this->lexers as $lexer) {
            if (null === $res = $lexer->tokenizeDate($string, $result->getOffset())) {
                // A lexer failed to match : exit.
                return null;
            }

            $result->merge($res);
        }

        return $result;
    }
}
