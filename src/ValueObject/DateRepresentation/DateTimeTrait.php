<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\Time;

/**
 * Popy\Calendar\ValueObject\DateTimeRepresentationInterface implementation.
 */
trait DateTimeTrait
{
    /**
     * Time.
     *
     * @var Time
     */
    protected $time;

    /**
     * @inheritDoc
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @inheritDoc
     */
    public function withTime(Time $time)
    {
        $res = clone $this;
        $res->time = $time;

        return $res;
    }
}
