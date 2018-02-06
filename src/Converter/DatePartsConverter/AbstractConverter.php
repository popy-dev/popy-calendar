<?php

namespace Popy\Calendar\Converter\DatePartsConverter;

use OutOfBoundsException;
use InvalidArgumentException;
use Popy\Calendar\ValueObject\DateParts;
use Popy\Calendar\Converter\DatePartsConverterInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

abstract class AbstractConverter implements DatePartsConverterInterface
{
    /**
     * @inheritDoc
     */
    public function fromDayIndex(DateFragmentedRepresentationInterface $input, $dayIndex)
    {
        $fs = $this->getAllFragmentSizes($input);

        $res = [];

        foreach ($fs as $frag => $sizes) {
            foreach ($sizes as $k => $size) {
                if ($dayIndex < $size) {
                    $res[] = $k;
                    continue 2;
                }

                $dayIndex -= $size;
            }

            throw new InvalidArgumentException(
                'Input dayIndex too big for given fragment sizes'
            );
        }

        $res[] = $dayIndex;

        return new DateParts($res);
    }

    /**
     * @inheritDoc
     */
    public function toDayIndex(DateFragmentedRepresentationInterface $input, DateParts $parts)
    {
        $res = 0;
        $fs = $this->getAllFragmentSizes($input);

        foreach ($parts->all() as $key => $value) {
            if (!isset($fs[$key])) {
                $res += $value;
                break;
            }

            if (!isset($fs[$key][(int)$value])) {
                throw new OutOfBoundsException(sprintf(
                    '%s is an index too big for fragment #%s',
                    $value,
                    $key
                ));
            }

            $res += $fs[$key][(int)$value];
        }

        return $res;
    }

    /**
     * Returns an array containing, for each fragment, all its possible sizes in
     * days.
     *
     * For instance, for the standard gregorian calendar, should return
     * [ [31,28,31, ...] ]
     *
     * @param DateFragmentedRepresentationInterface $input
     *
     * @return array<array><integer>
     */
    abstract protected function getAllFragmentSizes(DateFragmentedRepresentationInterface $input);
}
