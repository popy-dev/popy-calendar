<?php

namespace Popy\Calendar;

use DateTimeInterface;

/**
 * Utility/Helper class : Stores a preset date formatting to be used as a quick formatter.
 */
class PresetFormatter
{
    /**
     * Formatter.
     *
     * @var Formatter
     */
    protected $formatter;
    
    /**
     * Preset format.
     *
     * @var string
     */
    protected $format;

    /**
     * Class constructor.
     *
     * @param FormatterInterface $Formatter Formatter
     * @param string            $format   Date format
     */
    public function __construct(FormatterInterface $formatter, $format)
    {
        $this->formatter = $formatter;
        $this->format   = $format;
    }

    /**
     * Format the input date.
     *
     * @param DateTimeInterface $input
     *
     * @return string
     */
    public function format(DateTimeInterface $input)
    {
        return $this->formatter->format($input, $this->format);
    }
}
