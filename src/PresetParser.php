<?php

namespace Popy\Calendar;

use DateTimeInterface;

/**
 * Utility/Helper class : Stores a preset date formatting to be used as a quick parser.
 */
class PresetParser
{
    /**
     * Parser.
     *
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Preset format.
     *
     * @var string
     */
    protected $format;

    /**
     * Class constructor.
     *
     * @param ParserInterface $parser parser
     * @param string          $format   Date format
     */
    public function __construct(ParserInterface $parser, $format)
    {
        $this->parser = $parser;
        $this->format = $format;
    }

    /**
     * Parses the input string into a date.
     *
     * @param string $input
     *
     * @return DateTimeInterface
     */
    public function parse($input)
    {
        return $this->parser->parse($input, $this->format);
    }
}
