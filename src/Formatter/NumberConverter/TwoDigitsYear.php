<?php

namespace Popy\Calendar\Formatter\NumberConverter;

use Popy\Calendar\Formatter\NumberConverterInterface;

/**
 * Converts a number (typically a year) to a 2 digits representation, and back
 * from it, using a referenceYear.
 */
class TwoDigitsYear implements NumberConverterInterface
{
    /**
     * Reference century year.
     *
     * @var integer
     */
    protected $referenceYear;

    /**
     * @var boolean
     */
    protected $lateFiftyYearsArePreviousCentury;

    /**
     * Class constructor.
     *
     * @param integer $referenceYear                    Reference century year.
     * @param boolean $lateFiftyYearsArePreviousCentury If true, input values greater than 50 will
     *                                                  be resolved as previous century.
     */
    public function __construct($referenceYear = 2000, $lateFiftyYearsArePreviousCentury = true)
    {
        $this->referenceYear = $referenceYear - $referenceYear % 100;
        $this->lateFiftyYearsArePreviousCentury = $lateFiftyYearsArePreviousCentury;
    }

    /**
     * @inheritDoc
     */
    public function to($input)
    {
        return sprintf('%02d', $input % 100);
    }

    /**
     * @inheritDoc
     */
    public function from($input)
    {
        $input = intval($input);

        if ($this->lateFiftyYearsArePreviousCentury && $input > 50) {
            return $this->referenceYear - 100 + $input;
        }

        return $this->referenceYear + $input;
    }
}
