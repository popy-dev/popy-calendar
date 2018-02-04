<?php

namespace Popy\Calendar\Parser\DateLexer;

use BadMethodCallException;
use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\PregDateLexerInterface;

/**
 * Collection/Chain implementation of Preg lexer. It differs from a standard
 * collection on few points :
 *  - accept only PregDateLexerInterface lexers
 *  - will not call tokenizeDate on lexers
 *  - will integrate sub lexers' patterns into it's own, in order to perform
 *      a single preg_match
 * At the end, this collection will perform 3x faster than the standard
 * Collection implementation with the same internal lexers.
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
     * @param PregDateLexerInterface $lexer
     */
    public function register(PregDateLexerInterface $lexer)
    {
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
    }

    /**
     * @inheritDoc
     */
    public function getExpression()
    {
        $this->compile();

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
