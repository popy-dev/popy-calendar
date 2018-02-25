<?php

namespace Popy\Calendar\ValueObject;

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
     * Has the fragment been halved (AM would translate in false, PM in true).
     * Each fragment can have one.
     *
     * @var array<boolean|null>
     */
    protected $halved = [];

    /**
     * Class constructor.
     *
     * @param array        $fragments
     * @param array        $sizes
     * @param integer|null $ratio
     */
    public function __construct(array $fragments = [], array $sizes = [], $ratio = null)
    {
        parent::__construct($fragments, $sizes);

        $this->ratio = $ratio;
    }

    /**
     * Get time fragment FIXED VALUE, checking if it has been halved or not.
     *
     * @param integer $i
     *
     * @return integer|null
     */
    public function get($i)
    {
        if (!isset($this->fragments[$i])) {
            return;
        }

        if (
            !isset($this->halved[$i])   // not halved
            || !$this->fragments[$i]    // not set
            || !isset($this->sizes[$i]) // no solvable
        ) {
            return $this->fragments[$i];
        }

        $half = (int)floor($this->sizes[$i] / 2);

        $value = $this->fragments[$i];

        // Special case for g & h formats handling : if value is the same
        // as the half, which is not a possible value due to division,
        // ti means it is 0
        if ($value === $half) {
            $value = 0;
        }

        if ($this->halved[$i]) {
            $value += $half;
        }

        return $value;
    }

    /**
     * Get time fragment minus half of the fragment size, if possible.
     * eg: would return 2 out of 14 on a 24 long fragment.
     *
     * @param integer $i Fragment index
     *
     * @return integer
     */
    public function getHalved($i)
    {
        if (!isset($this->fragments[$i])) {
            return;
        }
        if (!isset($this->sizes[$i])) {
            return $this->fragments[$i];
        }

        $half = (int)floor($this->sizes[$i] / 2);

        return $this->fragments[$i] % $half;
    }

    public function canBeHalved($i)
    {
        if (isset($this->halved[$i])) {
            return $this->halved[$i];
        }

        if (
            !isset($this->fragments[$i])
            || !isset($this->sizes[$i])
        ) {
            return;
        }

        $half = (int)floor($this->sizes[$i] / 2);

        return $this->fragments[$i] >= $half;
    }

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

    /**
     * Checks if a fragment was halved
     *
     * @param integer $i
     *
     * @return boolean|null
     */
    public function isHalved($i)
    {
        if (isset($this->halved[$i])) {
            return $this->halved[$i];
        }
    }

    /**
     * Get all halved.
     *
     * @return array<boolean|null>
     */
    public function allHalved()
    {
        return $this->halved;
    }

    /**
     * Set time fragment "halved", adding null values if needed.
     *
     * @param integer      $index
     * @param boolean|null $value
     */
    public function withHalved($index, $value)
    {
        $res = clone $this;

        $res->halved = $this->insertInList($res->halved, $index, $value);

        return $res;
    }

    /**
     * Set all halved, adding null values if needed.
     *
     * @param array<bool|null> $halved
     *
     * @return static a new instance.
     */
    public function withHalveds(array $halved)
    {
        $res = clone $this;

        $this->halved = $this->fillArrayInput($halved);

        return $res;
    }
}
