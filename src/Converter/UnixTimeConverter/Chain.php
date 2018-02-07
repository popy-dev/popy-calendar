<?php

namespace Popy\Calendar\Converter\UnixTimeConverter;

use Popy\Calendar\Converter\Conversion;
use Popy\Calendar\Converter\UnixTimeConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Standard;

class Chain implements UnixTimeConverterInterface
{
    /**
     * Converter chain.
     *
     * @var array<UnixTimeConverterInterface>
     */
    protected $converters = [];
    
    /**
     * Class constructor.
     *
     * @param iterable<UnixTimeConverterInterface> $converters Converter chain.
     */
    public function __construct($converters = [])
    {
        $this->addConverters($converters);
    }
    
    /**
     * Adds a Converter to the chain.
     *
     * @param UnixTimeConverterInterface $converter
     */
    public function addConverter(UnixTimeConverterInterface $converter)
    {
        $this->converters[] = $converter;
    
        return $this;
    }
    
    /**
     * Add converters to the chain.
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
    public function fromUnixTime(Conversion $conversion)
    {
        foreach ($this->converters as $converter) {
            $converter->fromUnixTime($conversion);
        }
    }

    /**
     * @inheritDoc
     */
    public function toUnixTime(Conversion $conversion)
    {
        foreach (array_reverse($this->converters) as $converter) {
            $converter->toUnixTime($conversion);
        }
    }
}
