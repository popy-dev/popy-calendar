<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use Popy\Calendar\Converter\Time;
use Popy\Calendar\Converter\DateTimeRepresentationInterface;

/**
 * Minimal abstract implementatuon.
 */
abstract class AbstractDateTime extends AbstractDate implements DateTimeRepresentationInterface
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
