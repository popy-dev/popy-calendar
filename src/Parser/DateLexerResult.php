<?php

namespace Popy\Calendar\Parser;

/**
 * DateLexerResult
 */
class DateLexerResult
{
    /**
     * Resulting offset.
     *
     * @var integer
     */
    protected $offset;

    /**
     * Data extracted by the lexer.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Class constructor.
     *
     * @param integer $offset [description]
     */
    public function __construct($offset)
    {
        $this->offset = $offset;
    }

    /**
     * Gets offset.
     *
     * @return integer
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set parsed symbol value.
     *
     * @param string $name  Symbol name.
     * @param mixed  $value Symbol value.
     */
    public function set($name, $value)
    {
        $this->data[$name] = $value;

        return $this;
    }

    /**
     * Gets a symbol value.
     *
     * @param string $name    Symbol name.
     * @param mixed  $default Fallback value.
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        return $default;
    }
}
