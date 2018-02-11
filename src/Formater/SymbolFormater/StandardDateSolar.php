<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\Formater\NumberConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateSolarRepresentationInterface;

/**
 * Standard format, handling DateSolarRepresentationInterface.
 */
class StandardDateSolar implements SymbolFormaterInterface
{
    /**
     * Number converter.
     *
     * @var NumberConverterInterface
     */
    protected $converter;

    /**
     * Class constructor.
     *
     * @param NumberConverterInterface $converter Number converter.]
     */
    public function __construct(NumberConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
    {
        if (!$input instanceof DateSolarRepresentationInterface) {
            return;
        }
        if ($token->is('y')) {
            // y   A two digit representation of a year
            return $this->converter->to($input->getYear());
        }

        if ($token->is('Y')) {
            // Y   A full numeric representation of a year, 4 digits
            return sprintf('%04d', $input->getYear());
        }

        if ($token->is('L')) {
            // L   Whether it's a leap year
            return (string)(int)$input->isLeapYear();
        }

        if ($token->is('z')) {
            // z   The day of the year (starting from 0)
            return (string)$input->getDayIndex();
        }
    }
}
