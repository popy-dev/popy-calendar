<?php

namespace Popy\Calendar\Parser\DateLexer;

use InvalidArgumentException;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerResult;

/**
 * Simple Preg lexer implementation : will match a single pattern and associate
 * it with the input token if it's a symbol.
 *
 * Litterals and EOF are also handled, but the input pattern is not used.
 */
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
     * Match processing callback.
     *
     * @var callable
     */
    protected $callback;

    /**
     * Class constructor.
     *
     * @param FormatToken $token   Token to match.
     * @param string|null $pattern Preg pattern.
     */
    public function __construct(FormatToken $token, $pattern = null)
    {
        if ($token->isLitteral()) {
            $this->expression = preg_quote($token->getValue(), '/');
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
            $this->symbol = $token->getName();
            $this->expression = '(' . $pattern . ')';
            return;
        }

        throw new InvalidArgumentException(
            'Unsupported token type : ' . $token->getType()
        );
    }

    /**
     * Match processing callback.
     *
     * The callback will receive the Lexer and the matched string as input, and
     * must return the value to hydrate in the result.
     *
     * @param callable $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
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
            $res = $match[$offset][0];

            if ($this->callback !== null) {
                $res = call_user_func($this->callback, $this, $res);
            }

            $result->set($this->symbol, $res);
        }

        return $offset + 1;
    }
}
