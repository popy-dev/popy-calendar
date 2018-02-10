<?php

namespace Popy\Calendar\Parser\ResultMapper;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Chain implementation.
 */
class Chain implements ResultMapperInterface
{
    /**
     * Mapper chain.
     *
     * @var array<ResultMapperInterface>
     */
    protected $mappers = [];
    
    /**
     * Class constructor.
     *
     * @param iterable<ResultMapperInterface> $mappers Mapper chain.
     */
    public function __construct($mappers = [])
    {
        $this->addMappers($mappers);
    }
    
    /**
     * Adds a Mapper to the chain.
     *
     * @param ResultMapperInterface $mapper
     */
    public function addMapper(ResultMapperInterface $mapper)
    {
        if ($mapper instanceof self) {
            return $this->addMappers($mapper->mappers);
        }

        $this->mappers[] = $mapper;
    
        return $this;
    }
    
    /**
     * Add mappers to the chain.
     *
     * @param iterable<ResultMapperInterface> $mappers
     */
    public function addMappers($mappers)
    {
        foreach ($mappers as $mapper) {
            $this->addMapper($mapper);
        }
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date)
    {
        foreach ($this->mappers as $mapper) {
            if (null === $date = $mapper->map($parts, $date)) {
                return;
            }
        }

        return $date;
    }
}
