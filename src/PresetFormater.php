<?php

namespace Popy\Calendar;

use DateTimeInterface;

/**
 * Utility/Helper class : Stores a preset date formatting to be used as a quick formater.
 */
class PresetFormater
{
    /**
     * Formater.
     *
     * @var Formater
     */
    protected $formater;
    
    /**
     * Preset format.
     *
     * @var string
     */
    protected $format;

    /**
     * Class constructor.
     *
     * @param FormaterInterface $Formater Formater
     * @param string            $format   Date format
     */
    public function __construct(FormaterInterface $formater, $format)
    {
        $this->formater = $formater;
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
        return $this->formater->format($input, $this->format);
    }
}
