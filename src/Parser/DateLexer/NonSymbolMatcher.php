<?php

namespace Popy\Calendar\Parser\DateLexer;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\DateLexerInterface;

/**
 * Very basic lexer, matching a single token as a litteral one.
 */
class NonSymbolMatcher implements DateLexerInterface
{
    /**
     * Token.
     *
     * @var FormatToken
     */
    protected $token;

    /**
     * Class constructor.
     *
     * @param FormatToken $token
     */
    public function __construct(FormatToken $token)
    {
        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    public function tokenizeDate($string, $offset = 0)
    {
        if ($this->token->isType(FormatToken::TYPE_EOF)) {
            if (strlen($string) <= $offset) {
                return new DateLexerResult($offset);
            }

            return;
        }

        $len = strlen($this->token->getValue());

        if (substr($string, $offset, $len) === $this->token->getValue()) {
            return new DateLexerResult($offset + $len);
        }
    }
}
