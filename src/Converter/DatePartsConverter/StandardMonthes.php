<?php

namespace Popy\Calendar\Converter\DatePartsConverter;

use InvalidArgumentException;
use Popy\Calendar\Converter\SolarTimeRepresentationInterface;
use Popy\Calendar\Converter\DateFragmentedRepresentationInterface;

/**
 * Implementation of the standard (gregorian like) month calculation.
 */
class StandardMonthes extends AbstractConverter
{
    /**
     * @inheritDoc
     */
    protected function getAllFragmentSizes(DateFragmentedRepresentationInterface $input)
    {
        if (!$input instanceof SolarTimeRepresentationInterface) {
            throw new InvalidArgumentException(sprintf(
                'This converter can only handle "%s" objects, "%s" given',
                SolarTimeRepresentationInterface::class,
                get_class($input)
            ));
        }

        $leap = (int)$input->isLeapYear();

        return [
            [31, 28 + $leap, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
        ];
    }
}
