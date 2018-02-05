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
     * Class constructor.
     *
     * @param array $fragments
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

        return $res;
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
}
