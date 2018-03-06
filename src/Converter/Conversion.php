<?php

namespace Popy\Calendar\Converter;

use DateTimeInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

class Conversion
{
    /**
     * Date to convert.
     *
     * @var DateRepresentationInterface
     */
    protected $from;

    /**
     * Converted date (being built). Holds the date being built during a
     * conversion from unix time, and an eventually completed copy of the
     * source date.
     *
     * @var DateRepresentationInterface
     */
    protected $to;

    /**
     * Remaining/Accumulated unix time during conversion from/to timestamp.
     *
     * @var integer|null
     */
    protected $unixTime;

    /**
     * Remaining/Accumulated unix microtime during conversion from/to timestamp.
     *
     * @var integer|null
     */
    protected $unixMicroTime;

    /**
     * Class constructor.
     *
     * @param DateRepresentationInterface      $from
     * @param DateRepresentationInterface|null $to
     */
    public function __construct(DateRepresentationInterface $from, DateRepresentationInterface $to = null)
    {
        $this->from = $from;
        $this->to = $to ?: $from;
        $this->unixTime = $from->getUnixTime();
        $this->unixMicroTime = $from->getUnixMicroTime();
    }

    /**
     * Gets the Date to convert.
     *
     * @return DateRepresentationInterface
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Gets the Converted date.
     *
     * @return DateRepresentationInterface
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets the Converted date.
     *
     * @param DateRepresentationInterface $to the to
     *
     * @return self
     */
    public function setTo(DateRepresentationInterface $to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Gets unix time.
     *
     * @return integer
     */
    public function getUnixTime()
    {
        return (int)$this->unixTime;
    }

    /**
     * Sets unix time.
     *
     * @param integer $unixTime the unix time
     *
     * @return self
     */
    public function setUnixTime($unixTime)
    {
        $this->unixTime = $unixTime;

        return $this;
    }

    /**
     * Gets unix micro time.
     *
     * @return integer|null
     */
    public function getUnixMicroTime()
    {
        return $this->unixMicroTime;
    }

    /**
     * Sets unix micro time.
     *
     * @param integer $unixMicroTime the unix microtime
     *
     * @return self
     */
    public function setUnixMicroTime($unixMicroTime)
    {
        $this->unixMicroTime = $unixMicroTime;

        return $this;
    }
}
