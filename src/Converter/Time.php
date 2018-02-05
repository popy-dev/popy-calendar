<?php

namespace Popy\Calendar\Converter;

use InvalidArgumentException;

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
class Time
{
    /**
     * Time fragments.
     *
     * @var array<integer|null>
     */
    protected $fragments = [];

    /**
     * Reference period ratio multiplied by 1 000 000 (to keep and integer)
     *
     * @var integer|null
     */
    protected $ratio;

    /**
     * Class constructor.
     *
     * @param array $fragments [description]
     */
    public function __construct(array $fragments = [])
    {
        $this->setFragments($fragments);
    }

    /**
     * Get time fragment.
     *
     * @param integer $i
     *
     * @return integer|null
     */
    public function get($i)
    {
        if (isset($this->fragments[$i])) {
            return $this->fragments[$i];
        }
    }

    /**
     * Get all fragments.
     *
     * @return array<integer|null>
     */
    public function all()
    {
        return $this->fragments;
    }

    /**
     * Get all sequencial non-null fragments (which are actually exploitable to
     * determine time)
     *
     * @return array<integer>
     */
    public function getAllMeaningfull()
    {
        $res = [];

        foreach ($this->fragments as $value) {
            if (null === $value) {
                break;
            }

            $res[] = $value;
        }

        return $res;
    }

    /**
     * Set time fragment, adding null fragments if needed.
     *
     * @param integer      $index
     * @param integer|null $value
     */
    public function with($index, $value)
    {
        $res = clone $this;

        for ($i=count($res->fragments); $i < $index; $i++) { 
            $res->fragments[$i] = null;
        }

        $res->fragments[$index] = $value;

        return $this;
    }

    /**
     * Set all fragments, adding null fragments if needed.
     *
     * @param array<integer|null> $fragments
     *
     * @return static a new instance.
     */
    public function withFragments(array $fragments)
    {
        $res = clone $this;

        $res->setFragments($fragments);

        return $res
    }

    /**
     * Set fragments, adding null fragments if needed.
     *
     * @param array<integer|null> $fragments
     */
    protected function setFragments(array $fragments)
    {
        if (empty($fragments)) {
            $this->fragments = [];

            return;
        }

        $this->fragments = array_fill(0, max(array_keys($fragments)), null);

        foreach ($fragments as $index => $value) {
            $this->fragments[$index] = $value;
        }
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
        $res->ratio = $ration;

        return $res;
    }
}
