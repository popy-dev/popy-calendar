<?php

namespace Popy\Calendar\Calendar;

use DateTimeZone;
use DateTimeInterface;
use Popy\Calendar\CalendarInterface;
use Popy\Calendar\FormatterInterface;
use Popy\Calendar\ParserInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Composition implementation.
 */
class ComposedCalendar implements CalendarInterface
{
    /**
     * Formatter
     *
     * @var FormatterInterface
     */
    protected $formatter;

    /**
     * Parser
     *
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Class constructor.
     *
     * @param FormatterInterface $formatter
     * @param ParserInterface   $parser
     */
    public function __construct(FormatterInterface $formatter, ParserInterface $parser)
    {
        $this->formatter = $formatter;
        $this->parser   = $parser;
    }

    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        return $this->formatter->format($input, $format);
    }

    /**
     * @inheritDoc
     */
    public function formatDateRepresentation(DateRepresentationInterface $input, $format)
    {
        return $this->formatter->formatDateRepresentation($input, $format);
    }

    /**
     * @inheritDoc
     */
    public function parse($input, $format, DateTimeZone $timezone = null)
    {
        return $this->parser->parse($input, $format, $timezone);
    }
    
    /**
     * @inheritDoc
     */
    public function parseToDateRepresentation($input, $format, DateTimeZone $timezone = null)
    {
        return $this->parser->parseToDateRepresentation($input, $format, $timezone);
    }
}
