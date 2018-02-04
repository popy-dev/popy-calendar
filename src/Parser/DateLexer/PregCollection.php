<?php

namespace Popy\Calendar\Parser\DateLexer;

use BadMethodCallException;
use InvalidArgumentException;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\PregDateLexerInterface;
/**
 * PregMatchPattern uses preg_match to tokenize dates.
 *
 * To build one, either give non-null pattern to the constructor, or use
 * the progressive building methods regiser & close.
 *
 * This lexer handles self nesting.
 */
class PregCollection extends AbstractPreg
{
    /**
     * Currently built expression.
     *
     * @var string
     */
    protected $regexp = '';

    /**
     * Registered lexers.
     *
     * @var array<PregDateLexerInterface>
     */
    protected $lexers = [];

    /**
     * Built expressions.
     *
     * @var array<string>
     */
    protected $expressions = [];

    /**
     * Final compiled regular expression.
     *
     * @var string|null
     */
    protected $compiled;

    /**
     * Registers a pattern in the collection.
     *
     * @throws BadMethodCallException if called while the pattern has already
     *             been compiled
     *
     * @param PregDateLexerInterface $lexer
     */
    public function register(PregDateLexerInterface $lexer)
    {
        if ($this->compiled !== null) {
            throw new BadMethodCallException(
                'You can\'t register any new symbol once the object is compiled'
            );
        }

        $this->regexp .= $lexer->getExpression();
        $this->lexers[] = $lexer;
    }

    /**
     * Closes/finishes the current expression, by adding it to the list of
     * finished expressions and resetting it in case another expression has to
     * be built.
     */
    public function close()
    {
        if ($this->regexp) {
            $this->expressions[] = $this->regexp;
            $this->regexp = '';
        }
    }

    /**
     * Compile internal expression into the final one.
     */
    public function compile()
    {
        if ($this->compiled !== null) {
            return;
        }

        $this->compiled = $this->compileExpressions();
        $this->regexp = '';
        $this->expressions = [];
    }

    /**
     * @inheritDoc
     */
    public function getExpression()
    {
        return $this->compiled;
    }

    /**
     * @inheritDoc
     */
    public function hydrateResult(DateLexerResult $result, $match, $offset = 1)
    {
        foreach ($this->lexers as $lexer) {
            // No more matches : useless to continue
            if (!isset($match[$offset])) {
                return $offset;
            }

            $offset = $lexer->hydrateResult($result, $match, $offset);
        }

        return $offset;
    }

    /**
     * Compile expression list.
     *
     * @return string
     */
    protected function compileExpressions()
    {
        if (count($this->expressions) === 0) {
            throw new BadMethodCallException('Can\'t compile an empty expression');
        }
        if (count($this->expressions) === 1) {
            return reset($this->expressions);
        }

        $parts = [];

        foreach ($this->expressions as $expr) {
            $expr = '(?:' . $expr . ')';

            $parts[] = $expr;
        }

        return '(?:' . implode('|', $parts) . ')';
    }
}
