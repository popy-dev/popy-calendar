<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use DateTimeInterface;
use Popy\Calendar\ValueObject\TimeOffset;

/**
 * DateTimeINterface decorator.
 */
class DateTimeInterfaceWrapper extends AbstractDate
{
    /**
     * Date.
     *
     * @var DateTimeInterface
     */
    protected $datetime;

    /**
     * Class constructor.
     *
     * @param DateTimeInterface $datetime
     */
    public function __construct(DateTimeInterface $datetime)
    {
        $this->datetime = $datetime;
        $this->unixTime = $datetime->getTimestamp();
        $this->unixMicrotime = (int)$datetime->format('u');
        $this->timezone = $datetime->getTimezone();
        $this->offset = TimeOffset::buildFromDateTimeInterface($datetime);
    }
}
