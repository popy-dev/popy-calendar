<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use Popy\Calendar\Converter\Time;

/**
 * Popy\Calendar\Converter\DateTimeRepresentationInterface implementation.
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
