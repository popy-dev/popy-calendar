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
     * Sub converters.
     *
     * @var UnixTimeConverterInterface
     */
    protected $converters = [];

    /**
     * Adds a UnixTimeConverterInterface
     *
     * @param UnixTimeConverterInterface $converter
     */
    public function addConverter(UnixTimeConverterInterface $converter)
    {
        $this->converters[] = $converter;

        return $this;
    }

    /**
     * Adds a list of UnixTimeConverterInterface
     *
     * @param iterable<UnixTimeConverterInterface> $converters
     */
    public function addConverters($converters)
    {
        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }

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

        foreach ($this->converters as $converter) {
            $converter->fromUnixTime($conversion);
        }

        return $conversion->getTo();
    }

    /**
     * @inheritDoc
     */
    public function toDateTimeInterface(DateRepresentationInterface $input)
    {
        $conversion = new Conversion($input, $input);

        foreach (array_reverse($this->converters) as $converter) {
            $converter->toUnixTime($conversion);
        }

        $timestamp = sprintf(
            '%d.%06d UTC',
            $converter->getUnixTime(),
            $converter->getUnixMicroTime()
        );

        return DateTimeImmutable::createFromFormat('U.u e', $timestamp)
            ->setTimezone($input->getTimezone())
        ;
    }
}
