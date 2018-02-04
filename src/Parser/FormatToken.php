<?php

namespace Popy\Calendar\Parser;

/**
 * Format Token used by FormatLexer & FormatParser. Immutable.
 */
class FormatToken
{
    const TYPE_LITTERAL = 1;
    const TYPE_SYMBOL = 2;
    const TYPE_EOF = 3;

    /**
     * Token value.
     *
     * @var string|null
     */
    protected $value;

    /**
     * Token type.
     *
     * @var integer
     */
    protected $type;

    /**
     * Class constructor.
     *
     * @param string|null $value Token value.
     * @param integer     $type  Token type.
     */
    public function __construct($value, $type)
    {
        $this->value = $value;
        $this->type  = $type;
    }

    /**
     * Gets the token value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks if token is a TYPE_SYMBOL matching the input symbol.
     *
     * @param string $symbol
     *
     * @return boolean
     */
    public function is($symbol)
    {
        return $this->type === self::TYPE_SYMBOL && $this->value === $symbol;
    }

    /**
     * Checks if token is a TYPE_SYMBOL matching one of the arguments,
     * or one of the symbol contained in the first argument if it is an array
     * 
     * @param array|string $symbols 
     * @param string       ...$symbol
     *
     * @return boolean
     */
    public function isOne($symbols)
    {
        if ($this->type !== self::TYPE_SYMBOL) {
            return false;
        }

        if (!is_array($symbols)) {
            $symbols = func_get_args();
        }

        return in_array($this->value, $symbols);
    }

    /**
     * Set type.
     *
     * @param integer $type
     */
    public function setType($type)
    {
        $res = clone $this;

        $res->type = $type;

        return $res;
    }

    /**
     * Checks if token is a symbol.
     *
     * @return boolean
     */
    public function isSymbol()
    {
        return $this->type === self::TYPE_SYMBOL;
    }

    /**
     * Checks if symbol is litteral
     *
     * @return boolean
     */
    public function isLitteral()
    {
        return $this->type === self::TYPE_LITTERAL;
    }

    /**
     * Set litteral.
     */
    public function setLitteral()
    {
        return $this->setType(self::TYPE_LITTERAL);
    }

    /**
     * Checks if token is of given type.
     *
     * @param integer $type
     * 
     * @return boolean
     */
    public function isType($type)
    {
        return $this->type === $type;
    }

    /**
     * Get token type.
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
}
