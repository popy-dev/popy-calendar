<?php

namespace Popy\Calendar\Converter;

use DateTimeImmutable;
use DateTimeInterface;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateRepresentation\DateTimeInterfaceWrapper;

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

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fromDateTimeInterface(DateTimeInterface $input)
    {
        $conversion = new Conversion(
            new DateTimeInterfaceWrapper($input)
        );

        $this->converter->fromUnixTime($conversion);

        return $conversion->getTo();
    }

    /**
     * @inheritDoc
     */
    public function toDateTimeInterface(DateRepresentationInterface $input)
    {
        $conversion = new Conversion($input, $input);

        $this->converter->toUnixTime($conversion);

        $timestamp = sprintf(
            '%d.%06d UTC',
            $conversion->getUnixTime(),
            $conversion->getUnixMicroTime()
        );

        return DateTimeImmutable::createFromFormat('U.u e', $timestamp)
            ->setTimezone($input->getTimezone())
        ;
    }
}
