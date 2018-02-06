<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Chain/Collection implementation. Will delegate to internal formaters until
 * one of them returns a result.
 */
class Chain implements SymbolFormaterInterface
{
    /**
     * Formater chain.
     *
     * @var array<SymbolFormaterInterface>
     */
    protected $formaters = [];

    /**
     * Class constructor.
     *
     * @param iterable<SymbolFormaterInterface> $formaters
     */
    public function __construct($formaters = [])
    {
        $this->addFormaters($formaters);
    }

    /**
     * Adds a formater to the chain.
     *
     * @param SymbolFormaterInterface $formater
     */
    public function addFormater(SymbolFormaterInterface $formater)
    {
        $this->formaters[] = $formater;

        return $this;
    }

    /**
     * Add formaters to the chain.
     *
     * @param iterable<SymbolFormaterInterface> $formaters
     */
    public function addFormaters($formaters)
    {
        foreach ($formaters as $formater) {
            $this->addFormater($formater);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function formatSymbol(
        DateRepresentationInterface $input,
        FormatToken $token,
        FormaterInterface $formater
    ) {
        foreach ($this->formaters as $f) {
            if (null !== $res = $f->formatSymbol($input, $token, $formater)) {
                return $res;
            }
        }
    }
}
