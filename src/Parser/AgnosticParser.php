<?php

namespace Popy\Calendar\Parser;

use DateTimeZone;
use Popy\Calendar\ParserInterface;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\DateTimeZoneWrapper;

class AgnosticParser implements ParserInterface
{
    /**
     * Class constructor.
     *
     * @param FormatParserInterface $parser    Format parser.
     * @param ResultMapperInterface $mapper    Result mapper.
     * @param ConverterInterface    $converter Date converter.
     */
    public function __construct(FormatParserInterface $parser, ResultMapperInterface $mapper, ConverterInterface $converter)
    {
        $this->parser    = $parser;
        $this->mapper    = $mapper;
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function parse($input, $format, DateTimeZone $timezone = null)
    {
        $date = $this->parseToDateRepresentation($input, $format, $timezone);

        if (null === $date) {
            return;
        }

        return $this->converter->toDateTimeInterface($date);
    }

    /**
     * @inheritDoc
     */
    public function parseToDateRepresentation($input, $format, DateTimeZone $timezone = null)
    {
        if (null === $lexer = $this->parser->parseFormat($format)) {
            return;
        }

        if (null === $parts = $lexer->tokenizeDate($input)) {
            return;
        }

        $date = new DateTimeZoneWrapper($timezone);

        if (null === $date = $this->mapper->map($parts, $date)) {
            return;
        }

        return $date;
    }
}
