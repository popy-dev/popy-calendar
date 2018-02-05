<?php

namespace Popy\Calendar\ValueObject\DateRepresentation;

use Popy\Calendar\ValueObject\DateParts;

/**
 * Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface implementation.
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
