<?php

namespace Popy\Calendar\Formater;

use DateTimeInterface;
use Popy\Calendar\FormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

class NullFormater implements FormaterInterface
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
}
