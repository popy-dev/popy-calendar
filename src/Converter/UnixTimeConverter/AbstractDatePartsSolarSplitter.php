<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

abstract class AbstractDatePartsSolarSplitter implements UnixTimeConverterInterface
{
    /**
     * @inheritDoc
     */
    public function fromUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo();

        if (
            !$input instanceof DateFragmentedRepresentationInterface
            || !$input instanceof DateSolarRepresentationInterface
        ) {
            return;
        }

        $dayIndex = $input->getDayIndex();

        $fragments = $resultSizes = [];

        foreach ($this->getAllFragmentSizes($input) as $frag => $sizes) {
            foreach ($sizes as $k => $size) {
                if ($dayIndex < $size) {
                    $fragments[] = $k;
                    $resultSizes[] = $size;
                    continue 2;
                }

                $dayIndex -= $size;
            }

            throw new InvalidArgumentException(
                'Input dayIndex too big for given fragment sizes'
            );
        }

        $fragments[] = $dayIndex;


        $dateParts = $input->getDateParts()
            ->withFragments($fragments)
            ->withSizes($resultSizes)
        ;

        $conversion->setTo($input->withDateParts($dateParts));
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        $input = $conversion->getTo();

        if (
            !$input instanceof DateFragmentedRepresentationInterface
            || !$input instanceof DateSolarRepresentationInterface
        ) {
            return;
        }

        if (null !== $input->getDayIndex()) {
            return ;
        }

        $dayIndex = 0;
        $dateParts = $input->getDateParts();
        $fragmentSizes = $this->getAllFragmentSizes($input);

        foreach ($parts->all() as $index => $value) {
            if (!isset($fragmentSizes[$index])) {
                // No available size means we reached the final fragment
                $dayIndex += $value;
                break;
            }

            if (!isset($fragmentSizes[$index][(int)$value])) {
                throw new OutOfBoundsException(sprintf(
                    '%s is an index too big for fragment #%s',
                    $value,
                    $index
                ));
            }

            $dayIndex += $fragmentSizes[$index][(int)$value];
        }

        $conversion->setTo($input->withDayIndex($dayIndex));
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
