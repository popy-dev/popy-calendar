<?php

namespace Popy\Calendar\Converter;

use DateTimeZone;
use DateTimeInterface;

/**
 * Represents the time offset induced by timezones and day saving time.
 * It is an immutable object.
 *
 * A TimeOffset MAY NOT be aware of its properties (value, dst status,
 * abbreviation, usually when it's been built by a parser).
 */
class TimeOffset
{
    /**
     * Offset value.
     *
     * @var integer|null
     */
    protected $value;

    /**
     * Day saving time status.
     *
     * @var boolean|null
     */
    protected $dst;

    /**
     * Offset abbreviation.
     *
     * @var string|null
     */
    protected $abbreviation;

    /**
     * Class constructor.
     *
     * @param integer $value        Offset value.
     * @param boolean $dst          Day saving time status.
     * @param string  $abbreviation Offset abbreviation.
     */
    public function __construct($value, $dst, $abbreviation)
    {
        $this->value        = $value;
        $this->dst          = $dst;
        $this->abbreviation = $abbreviation;
    }

    /**
     * Instanciates a TimeOffset from a DateTimeInterface, using the format
     * method to extract properties.
     *
     * @param DateTimeInterface $date
     * 
     * @return static
     */
    public static function buildFromDateTimeInterface(DateTimeInterface $date)
    {
        $parts = explode('|', $date->format('Z|I|T'));

        return new static((int)$parts[0], (bool)$parts[1], $parts[2]);
    }

    /**
     * Gets the offset value.
     *
     * @return integer|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks day saving time status.
     *
     * @return boolean|null
     */
    public function isDst()
    {
        return $this->dst;
    }

    /**
     * Gets the Offset abbreviation..
     *
     * @return string|null
     */
    public function getAbbreviation()
    {
        return $this->abbreviation;
    }

    
    /**
     * Gets a new TimeOffset instance with the input value.
     *
     * @param integer $value
     *
     * @return static
     */
    public function withValue($value)
    {
        $res = clone $this;
        $res->value = $value;

        return $res;
    }
    
    /**
     * Gets a new TimeOffset instance with the input dst.
     *
     * @param boolean $dst
     *
     * @return static
     */
    public function withDst($dst)
    {
        $res = clone $this;
        $res->dst = $dst;

        return $res;
    }
    
    /**
     * Gets a new TimeOffset instance with the input abbreviation.
     *
     * @param integer $abbreviation
     *
     * @return static
     */
    public function withAbbreviation($abbreviation)
    {
        $res = clone $this;
        $res->abbreviation = $abbreviation;

        return $res;
    }

    /**
     * Build a DateTimeZone object based on TimeOffset properties, if possible.
     *
     * @return DateTimeZone|null
     */
    public function buildTimeZone()
    {
        if (null !== $this->abbreviation) {
            return new DateTimeZone($this->abbreviation);
        }

        if (null !== $this->value) {
            $sign = $this->value < 0 ? '-' : '+';
            $value = intval(abs($value) / 60);

            return new DateTimeZone(sprintf(
                '%s%02d:%02d',
                $sign,
                intval($value / 60),
                $value % 60
            ));
        }
    }
}
