<?php

namespace Popy\Calendar\Parser;

/**
 * Format Token used by FormatLexer & FormatParser. Immutable.
 */
class FormatToken
{
    /**
     * Symbol value.
     *
     * @var string
     */
    protected $symbol;

    /**
     * Is litteral.
     *
     * @var boolean
     */
    protected $litteral;

    /**
     * Class constructor.
     *
     * @param string  $symbol   Symbol value.
     * @param boolean $litteral Is litteral.
     */
    public function __construct($symbol, $litteral)
    {
        $this->symbol   = $symbol;
        $this->litteral = $litteral;
    }

    /**
     * Gets the Symbol value.
     *
     * @return string
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * Checks if symbol is litteral
     *
     * @return boolean
     */
    public function isLitteral()
    {
        return $this->litteral;
    }

    /**
     * Checks if token is a non litteral symbol matching the input symbol.
     *
     * @param string $symbol
     *
     * @return boolean
     */
    public function is($symbol)
    {
        return !$this->litteral && $this->symbol === $symbol;
    }

    /**
     * Checks if token is a non litteral symbol matching one of the arguments,
     * or one of the symbol contained in the first argument if it is an array
     * 
     * @param array|string $symbols 
     * @param string       ...$symbol
     *
     * @return boolean
     */
    public function isOne($symbols)
    {
        if ($this->litteral) {
            return false;
        }

        if (!is_array($symbols)) {
            $symbols = func_get_args();
        }

        return in_array($this->symbol, $symbols);
    }

    /**
     * Set litteral.
     *
     * @param boolean $litteral
     */
    public function setLitteral($litteral = true)
    {
        $res = clone $this;

        $res->litteral = $litteral;

        return $res;
    }
}