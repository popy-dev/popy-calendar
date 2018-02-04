<?php

namespace Popy\Calendar\Parser\DateLexer;

use InvalidArgumentException;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerResult;

class PregSimple extends AbstractPreg
{
    /**
     * Registered symbol.
     *
     * @var string|null
     */
    protected $symbol;

    /**
     * Expression.
     *
     * @var string
     */
    protected $expression;

    /**
     * Class constructor.
     *
     * @param FormatToken $token   Token to match.
     * @param string|null $pattern Preg pattern.
     */
    public function __construct(FormatToken $token, $pattern = null)
    {
        if ($token->isLitteral()) {
            $this->expression = preg_quote($token->getValue());
            return;
        }

        if ($token->isType(FormatToken::TYPE_EOF)) {
            $this->expression = '$';
            return;
        }

        if ($token->isSymbol()) {
            if ($pattern === null) {
                throw new InvalidArgumentException(
                    'You must supply a pattern for a Symbol token'
                );
            }
            $this->symbol = $token->getValue();
            $this->expression = '(' . $pattern . ')';
            return;
        }

        throw new InvalidArgumentException(
            'Unsupported token type : ' . $token->getType()
        );
    }

    /**
     * @inheritDoc
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @inheritDoc
     */
    public function hydrateResult(DateLexerResult $result, $match, $offset = 1)
    {
        if ($this->symbol === null || !isset($match[$offset])) {
            return $offset;
        }

        // Did match
        if ($match[$offset][1] !== -1) {
            $result->set($this->symbol, $match[$offset][0]);
        }

        return $offset + 1;
    }
}
