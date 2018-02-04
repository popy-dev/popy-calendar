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

    /**
     * Merges a result into this one.
     *
     * @param DateLexerResult $result
     */
    public function merge(DateLexerResult $result)
    {
        $this->offset = $result->offset;
        $this->data = array_merge($this->data, $result->data);
    }

    /**
     * Get first set symbol value.
     *
     * @param string $name 
     * @param string ...$name
     * 
     * @return mixed
     */
    public function getFirst($name)
    {
        $names = func_get_args();

        foreach ($names as $name) {
            if (isset($this->data[$name])) {
                return $this->data[$name];
            }
        }
    }
}
