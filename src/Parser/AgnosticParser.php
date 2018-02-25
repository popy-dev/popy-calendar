<?php

namespace Popy\Calendar\Parser;

use DateTimeZone;
use DateTimeImmutable;
use Popy\Calendar\ParserInterface;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Date;

/**
 * Agnostic composable parser implementation.
 */
class AgnosticParser implements ParserInterface
{
    /**
     * Format parser.
     *
     * @var FormatParserInterface
     */
    protected $parser;
    
    /**
     * Result mapper.
     *
     * @var ResultMapperInterface
     */
    protected $mapper;
    
    /**
     * Date converter.
     *
     * @var ConverterInterface
     */
    protected $converter;

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
        // Parsing
        $date = $this->parseToDateRepresentation($input, $format, $timezone);

        // Converting back if needed/possible
        if (null !== $date && null === $date->getUnixTime()) {
            $date = $this->converter->from($date);
        }

        if (null === $date) {
            return;
        }

        $timestamp = sprintf(
            '%d.%06d UTC',
            $date->getUnixTime(),
            $date->getUnixMicroTime()
        );

        $result = DateTimeImmutable::createFromFormat('U.u e', $timestamp);

        if (false === $result) {
            return;
        }

        return $result->setTimezone($date->getTimezone()) ?: null;
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

        $date = Date::buildFromTimezone($timezone);

        if (null === $date = $this->mapper->map($parts, $date)) {
            return;
        }

        return $date;
    }
}
