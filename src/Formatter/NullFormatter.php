<?php

namespace Popy\Calendar\Formatter;

use DateTimeInterface;
use Popy\Calendar\FormatterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

class NullFormatter implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function formatDateRepresentation(DateRepresentationInterface $input, $format)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function formatUnixTime($input, $format)
    {
        return '';
    }

    /**
     * @inheritDoc
     */
    public function formatMicrotime($input, $format)
    {
        return '';
    }
}
