<?php

namespace Popy\Calendar\Converter;

use DateTimeImmutable;
use DateTimeInterface;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Date;

/**
 * Agnostique date converter : relies on a list of UnixTimeConverterInterface.
 * Take care to inject converters in the proper order.
 */
class AgnosticConverter implements ConverterInterface
{
    /**
     * Converter.
     *
     * @var UnixTimeConverterInterface
     */
    protected $converter;

    /**
     * Class constructor.
     *
     * @param UnixTimeConverterInterface $converter
     */
    public function __construct(UnixTimeConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function to(DateRepresentationInterface $input)
    {
        $conversion = new Conversion($input);

        $this->converter->fromUnixTime($conversion);

        return $conversion->getTo();
    }

    /**
     * @inheritDoc
     */
    public function from(DateRepresentationInterface $input)
    {
        $conversion = new Conversion($input, $input);

        $this->converter->toUnixTime($conversion);

        return Date::fromConversion($conversion);
    }
}
