<?php

namespace Popy\Calendar\Parser\DateLexer;

use BadMethodCallException;
use InvalidArgumentException;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerInterface;

/**
 * PregMatchPattern uses preg_match to tokenize dates.
 *
 * To build one, either give non-null pattern to the constructor, or use
 * the progressive building methods regiser & close.
 */
class PregMatchPattern implements DateLexerInterface
{
    /**
     * Currently built expression.
     *
     * @var string
     */
    protected $regexp = '';

    /**
     * Registered symbols.
     *
     * @var array
     */
    protected $symbols = [];

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
     * Class constructor. If any pattern is given, will register $pattern &
     * $symbol, close and compile the lexer.
     *
     * @param string|null $pattern Regular expression
     * @param string|null $symbol  Symbol
     */
    public function __construct($pattern = null, $symbol = null)
    {
        if ($pattern !== null) {
            $this->register($pattern, $symbol);
            $this->close();
            $this->compile();
        }
    }

    /**
     * Adds a pattern and its related symbol into the current expression. If the
     * pattern is a DateLexerInterface, it will be included as a nested
     * subpattern. IF the pattern is a string and no symbol is given, the 
     * pattern will be considered as a litteral expression and will be escaped.
     *
     * @throws BadMethodCallException if called while the pattern has already
     *             been compiled
     * @throws InvalidArgumentException if any non compatible DateLexerInterface
     *             is given as pattern
     *
     * @param string|DateLexerInterface $pattern
     * @param string|null               $symbol
     */
    public function register($pattern, $symbol = null)
    {
        if ($this->compiled !== null) {
            throw new BadMethodCallException(
                'You can\'t register any new symbol once the object is compiled'
            );
        }

        if ($pattern instanceof self) {
            $this->regexp .= $pattern->getCompiledExpression();
            $this->symbols = array_merge($this->symbols, $pattern->getSymbols());
            return;
        }

        if ($pattern instanceof DateLexerInterface) {
            throw new InvalidArgumentException(
                'You can\'t nest another kind of lexer in ' . get_class($this)
            );
        }

        if ($symbol === null) {
            $this->regexp .= preg_quote($pattern);
            return;
        }

        $this->symbols[] = $symbol;
        $this->regexp .= '(' . $pattern . ')';
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
    public function tokenizeDate($string)
    {
        $this->compile();

        $match = $res = [];

        if (!preg_match(
            '/^'.$this->compiled.'$/',
            $string,
            $match,
            PREG_OFFSET_CAPTURE
        )) {
            return null;
        }

        foreach ($this->symbols as $key => $symbol) {
            if (!isset($res[$symbol])) {
                $res[$symbol] = null;
            }

            if (
                !isset($match[$key + 1])
                || $match[$key + 1][1] === -1
            ) {
                continue;
            }

            $res[$symbol] = $match[$key + 1][0];
        }

        return $res;
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

    /**
     * Gets the registered symbols.
     *
     * @return array
     */
    public function getSymbols()
    {
        return $this->symbols;
    }

    /**
     * Gets the Final compiled regular expression.
     *
     * @return string|null
     */
    public function getCompiledExpression()
    {
        return $this->compiled;
    }
}