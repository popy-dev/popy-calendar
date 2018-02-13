<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\LeapYearCalculatorInterface;
use Popy\Calendar\ValueObject\DateFragmentedRepresentationInterface;

/**
 * Equal length monthes implementation.
 */
class EqualLengthMonthes extends AbstractDatePartsSolarSplitter
{
    /**
     * Leap year calculator.
     *
     * @var LeapYearCalculatorInterface
     */
    protected $calculator;

    /**
     * Month length.
     *
     * @var integer
     */
    protected $length;

    /**
     * Class constructor.
     *
     * @param LeapYearCalculatorInterface $calculator Leap year calculator.
     * @param integer                     $length     Month length.
     */
    public function __construct(LeapYearCalculatorInterface $calculator, $length)
    {
        $this->calculator = $calculator;
        $this->length = $length;
    }

    /**
     * @inheritDoc
     */
    protected function getAllFragmentSizes(DateFragmentedRepresentationInterface $input)
    {
        $days = $this->calculator->getYearLength($input->getYear());
        $monthes = array_fill(
            0,
            intval($days / $this->length),
            $this->length
        );
        $monthes[] = $days % $this->length;

        return [$monthes];
    }
}
