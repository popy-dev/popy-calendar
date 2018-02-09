<?php

namespace Popy\Calendar\Calendar;

use DateTimeZone;
use DateTimeInterface;
use Popy\Calendar\CalendarInterface;
use Popy\Calendar\FormaterInterface;
use Popy\Calendar\ParserInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Composition implementation.
 */
class ComposedCalendar implements CalendarInterface
{
    /**
     * Formater
     *
     * @var FormaterInterface
     */
    protected $formater;

    /**
     * Parser
     *
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Class constructor.
     *
     * @param FormaterInterface $formater
     * @param ParserInterface   $parser
     */
    public function __construct(FormaterInterface $formater, ParserInterface $parser)
    {
        $this->formater = $formater;
        $this->parser   = $parser;
    }

    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        return $this->formater->format($input, $format);
    }

    /**
     * @inheritDoc
     */
    public function formatDateRepresentation(DateRepresentationInterface $input, $format)
    {
        return $this->formater->formatDateRepresentation($input, $format);
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
