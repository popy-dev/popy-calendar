<?php

namespace Popy\Calendar\Converter\DatePartsConverter;

use InvalidArgumentException;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

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
        if (!$input instanceof DateSolarRepresentationInterface) {
            throw new InvalidArgumentException(sprintf(
                'This converter can only handle "%s" objects, "%s" given',
                DateSolarRepresentationInterface::class,
                get_class($input)
            ));
        }

        $leap = (int)$input->isLeapYear();

        return [
            [31, 28 + $leap, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
        ];
    }
}
