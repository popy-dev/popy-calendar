<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Implementation of the standard (gregorian like) month calculation.
 */
class GregorianCalendarMonthes extends AbstractDatePartsSolarSplitter
{
    /**
     * @inheritDoc
     */
    protected function getAllFragmentSizes(DateFragmentedRepresentationInterface $input)
    {
        $leap = (int)$input->isLeapYear();

        return [
            [31, 28 + $leap, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
        ];
    }
}
