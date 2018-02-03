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
