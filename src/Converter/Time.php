<?php

namespace Popy\Calendar\Converter;

/**
 * A Time is a fragmented representation of a time period of fixed & static
 * length (usually solar days). It is an Immutable object.
 *
 * A Time MAY not be aware of all|some of its fragments, or of its reference's
 * period ratio. (usually when it's been built by a parser).
 *
 * A Time is not aware of the time format it holds, and MUST NOT check for
 * internal consistency. It is only a value holder, and calculation has to be
 * made by converters, or other components.
 */
class Time extends AbstractFragmentedDuration
{
    /**
     * Reference period ratio multiplied by 1 000 000 (to keep and integer)
     *
     * @var integer|null
     */
    protected $ratio;

    /**
     * Gets ratio.
     *
     * @return integer|null
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Gets a new instance with input ratio.
     *
     * @param integer|null $ratio
     *
     * @return static
     */
    public function withRatio($ratio)
    {
        $res = clone $this;
        $res->ratio = $ratio;

        return $res;
    }
}
