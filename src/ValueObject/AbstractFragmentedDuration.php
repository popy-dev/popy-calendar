<?php

namespace Popy\Calendar\ValueObject;

abstract class AbstractFragmentedDuration
{
    /**
     * Fragments.
     *
     * @var array<integer|null>
     */
    protected $fragments = [];

    /**
     * Fragment sizes.
     *
     * @var array<integer|null>
     */
    protected $sizes = [];

    /**
     * Class constructor.
     *
     * @param array $fragments
     * @param arra  $sizes
     */
    public function __construct(array $fragments = [], array $sizes = [])
    {
        $this->fragments = $this->fillArrayInput($fragments);
        $this->sizes = $this->fillArrayInput($sizes);
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
        $res = [];
        $len = count($this->fragments);

        for ($index=0; $index < $len; $index++) {
            $res[] = $this->get($index);
        }

        return $res;
    }

    /**
     * Count all sequencial non-null fragments (which are actually exploitable
     * to determine time)
     *
     * @return integer
     */
    public function countMeaningfull()
    {
        $res = 0;

        foreach ($this->fragments as $index => $value) {
            if (null === $value) {
                break;
            }

            $res++;
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

        $res->fragments = $this->insertInList($res->fragments, $index, $value);

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

        $res->fragments = $res->fillArrayInput($fragments);

        return $res;
    }

    /**
     * Get time fragment size.
     *
     * @param integer $i
     *
     * @return integer|null
     */
    public function getSize($i)
    {
        if (isset($this->sizes[$i])) {
            return $this->sizes[$i];
        }
    }

    /**
     * Get all fragment sizes.
     *
     * @return array<integer|null>
     */
    public function allSizes()
    {
        return $this->sizes;
    }

    /**
     * Set time fragment size, adding null values if needed.
     *
     * @param integer      $index
     * @param integer|null $value
     */
    public function withSize($index, $value)
    {
        $res = clone $this;

        $res->sizes = $this->insertInList($res->sizes, $index, $value);

        return $res;
    }

    /**
     * Set all sizes, adding null values if needed.
     *
     * @param array<integer|null> $sizes
     *
     * @return static a new instance.
     */
    public function withSizes(array $sizes)
    {
        $res = clone $this;

        $res->sizes = $res->fillArrayInput($sizes);

        return $res;
    }

    /**
     * Insrt a value in a list, inserting null values if needed to keep a
     * conscutive indexing.
     *
     * @param array        $values Actual values.
     * @param integer      $index  New value index.
     * @param integer|null $value  New value.
     *
     * @return array new value list.
     */
    public function insertInList(array $values, $index, $value)
    {
        for ($i=count($values); $i < $index; $i++) {
            $values[$i] = null;
        }

        $values[$index] = $value;

        return $values;
    }

    /**
     * Set fragments sizes, adding null sizes if needed.
     *
     * @param array<integer|null> $sizes
     */
    protected function fillArrayInput(array $input)
    {
        if (empty($input)) {
            return [];
        }

        $res = array_fill(0, max(array_keys($input)), null);

        foreach ($input as $index => $value) {
            $res[$index] = $value;
        }

        return $res;
    }
}
