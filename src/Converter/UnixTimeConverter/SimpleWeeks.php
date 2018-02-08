<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Simple weeks.
 */
class SimpleWeeks implements UnixTimeConverterInterface
{
    /**
     * Week length.
     *
     * @var integer
     */
    protected $length;

    /**
     * Class constructor.
     *
     * @param integer $length Week length.
     */
    public function __construct($length)
    {
        $this->length = $length;
    }

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

        $dateParts = $input->getDateParts()->withTransversals([
            $input->getYear(),
            intval($input->getDayIndex() / $this->length),
            $input->getDayIndex() % $this->length
        ]);

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

        $parts = $input->getDateParts();

        if (null === $input->getYear()) {
            $input = $input->withYear(
                $parts->getTransversal(0),
                $input->isLeapYear()
            );
        }

        if (
            null === $input->getDayIndex()
            && null !== $weekIndex = $parts->getTransversal(1)
        ) {
            $dayOfWeek = (int)$parts->getTransversal(2);

            $input = $input->withDayIndex(
                $dayOfWeek + $weekIndex * $this->length,
                $input->getEraDayIndex()
            );
        }

        $conversion->setTo($input);
    }
}
