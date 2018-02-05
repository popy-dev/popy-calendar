<?php

namespace Popy\Calendar\Converter\DateTimeRepresentation;

use Popy\Calendar\Converter\DateParts;

/**
 * Popy\Calendar\Converter\DateFragmentedRepresentationInterface implementation.
 */
trait DateFragmentedTrait
{
    /**
     * Date parts.
     *
     * @var DateParts
     */
    protected $dateParts;

    /**
     * @inheritDoc
     */
    public function getDateParts()
    {
        return $this->dateParts;
    }

    /**
     * @inheritDoc
     */
    public function withDateParts(DateParts $dateParts)
    {
        $res = clone $this;
        $res->dateParts = $dateParts;

        return $res;
    }
}
