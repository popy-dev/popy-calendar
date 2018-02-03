<?php

namespace Popy\Calendar\Parser\DateLexer;

use BadMethodCallException;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerInterface;

/**
 * PregMatchPattern uses preg_match to tokenize dates.
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

    public function register($pattern, $symbol = null)
    {
        if ($this->compiled !== null) {
            throw new BadMethodCallException(
                'You can\'t register any new symbol once the object is compiled'
            );
        }

        if ($symbol === null) {
            $this->regexp .= preg_quote($pattern);
            return;
        }

        if ($pattern instanceof self) {
            $this->regexp .= $pattern->compiled;
            $this->symbols = array_merge($this->symbols, $pattern->symbols);
            return;
        }

        $this->symbols[] = $symbol;
        $this->regexp .= '(' . $pattern . ')';
    }

    public function close()
    {
        if ($this->regexp) {
            $this->expressions[] = $this->regexp;
            $this->regexp = '';
        }
    }

    public function compile()
    {
        if ($this->compiled !== null) {
            return;
        }

        $this->regexp = '';
        $this->expressions = [];
        $this->compiled = $this->compileExpressions();
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