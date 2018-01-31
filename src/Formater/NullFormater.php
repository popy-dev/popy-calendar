<?php

namespace Popy\Calendar\Formater;

use DateTimeInterface;
use Popy\Calendar\FormaterInterface;

class NullFormater implements FormaterInterface
{
    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        return '';
    }
}
