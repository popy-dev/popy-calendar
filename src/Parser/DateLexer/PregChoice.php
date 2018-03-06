<?php

namespace Popy\Calendar\Parser\DateLexer;

use InvalidArgumentException;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\DateLexerResult;

class PregChoice extends AbstractPreg
{
    /**
     * Registered symbol.
     *
     * @var string|null
     */
    protected $symbol;

    /**
     * Choices.
     *
     * @var array<string>
     */
    protected $choices;

    /**
     * Expression.
     *
     * @var string
     */
    protected $expression;

    public function __construct(FormatToken $token, array $choices)
    {
        if (!$token->isSymbol()) {
            throw new InvalidArgumentException(
                'You must supply a Symbol token'
            );
        }

        $this->symbol = $token->getName();
        $this->choices = $choices;

        $parts = [];
        foreach ($choices as $part) {
            $parts[] = '(?i:' . preg_quote($part, '/') . ')';
        }

        $this->expression = '(' . implode('|', $parts) . ')';
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
        if (!isset($match[$offset])) {
            return $offset;
        }

        if (
            null === $this->symbol // nothing to map
            || $match[$offset][1] === -1 // didn't match
        ) {
            return $offset + 1;
        }

        // Did match
        $found = array_search($match[$offset][0], $this->choices);

        // DO THE MAGIC
        if ($found === false) {
            $res = preg_grep(
                '/^' . preg_quote($match[$offset][0], '/') . '$/i',
                $this->choices
            );

            if (count($res)) {
                reset($res);
                $found = key($res);
            }
        }
        
        $result->set($this->symbol, $found);

        return $offset + 1;
    }
}
