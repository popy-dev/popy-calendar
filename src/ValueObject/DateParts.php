<?php

namespace Popy\Calendar\ValueObject;

/**
 * A DateParts is a fragmented representation of a time period of variable
 * length (like gregorian monthes). It is an Immutable object.
 *
 * A DateParts MAY not be aware of all|some of its fragments. (usually when it's
 * been built by a parser).
 *
 * A DateParts is not aware of the time format it holds, and MUST NOT check for
 * internal consistency. It is only a value holder, and calculation has to be
 * made by converters, or other components.
 */
class DateParts extends AbstractFragmentedDuration
{
    /**
     * Transversal/parrallel units.
     *
     * @var array<integer|null>
     */
    protected $transversals = [];

    /**
     * Get transversal unit.
     *
     * @param integer $i
     *
     * @return integer|null
     */
    public function getTransversal($i)
    {
        if (isset($this->transversals[$i])) {
            return $this->transversals[$i];
        }
    }

    /**
     * Get all transversal units.
     *
     * @return array<integer|null>
     */
    public function allTransversals()
    {
        return $this->transversals;
    }

    /**
     * Set transversal unit, adding null values if needed.
     *
     * @param integer      $index
     * @param integer|null $value
     */
    public function withTransversal($index, $value)
    {
        $res = clone $this;

        $res->transversals = $this->insertInList($res->transversals, $index, $value);

        return $res;
    }

    /**
     * Set all transversal units, adding null values if needed.
     *
     * @param array<integer|null> $transversals
     *
     * @return static a new instance.
     */
    public function withTransversals(array $transversals)
    {
        $res = clone $this;

        $res->transversals = $res->fillArrayInput($transversals);

        return $res;
    }
}
