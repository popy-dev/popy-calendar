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
class PregMatchPattern extends AbstractPreg
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
    public function __construct(FormatToken $token = null, $pattern = null)
    {
        if ($token !== null) {
            $this->register($token, $pattern);
            $this->close();
            $this->compile();
        }
    }

    /**
     * Adds a pattern and its related symbol into the current expression. If the
     * pattern is a PregDateLexerInterface, it will be included as a nested
     * subpattern. IF the pattern is a string and no symbol is given, the 
     * pattern will be considered as a litteral expression and will be escaped.
     *
     * @throws BadMethodCallException if called while the pattern has already
     *             been compiled
     * @throws InvalidArgumentException if any non compatible PregDateLexerInterface
     *             is given as pattern
     *
     * @param FormatToken|null               $token
     * @param PregDateLexerInterface|string|null $pattern
     */
    public function register(FormatToken $token, PregDateLexerInterface $pattern)
    {
        if ($this->compiled !== null) {
            throw new BadMethodCallException(
                'You can\'t register any new symbol once the object is compiled'
            );
        }

        if ($pattern instanceof PregDateLexerInterface) {
            $this->regexp .= $pattern->getExpression();
            $this->symbols[] = $pattern;
            return;
        }

        if (is_object($pattern)) {
            throw new InvalidArgumentException(
                'You can\'t register an ' . get_class($pattern) . ' instance !'
            );
        }

        if ($token->isLitteral()) {
            $this->regexp .= preg_quote($token->getValue());
            return;
        }

        if ($token->isSymbol()) {
            $this->symbols[] = $token->getValue();
            $this->regexp .= '(' . $pattern . ')';
            return;
        }

        if ($token->isType(FormatToken::TYPE_EOF)) {
            $this->regexp .= '$';
            return;
        }
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
        foreach ($this->symbols as $symbol) {
            if ($symbol instanceof PregDateLexerInterface) {
                $offset = $symbol->hydrateResult($result, $match, $offset);
                continue;
            }

            // No more matches : useless to continue
            if (!isset($match[$offset])) {
                return $offset;
            }

            // Did match
            if ($match[$offset][1] !== -1) {
                $result->set($symbol, $match[$offset][0]);
            }
            
            $offset++;
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
