<?php

namespace Popy\Calendar\Formater\SymbolFormater;

use Popy\Calendar\FormaterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formater\SymbolFormaterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Handles Litteral tokens.
 */
class Litteral implements SymbolFormaterInterface
{
    /**
     * Will consider symbols (not handled by previous formaters) as litterals
     *
     * @var boolean
     */
    protected $considerSymbolsAsLitterals;

    /**
     * Class constructor.
     *
     * @param boolean $considerSymbolsAsLitterals
     */
    public function __construct($considerSymbolsAsLitterals = false)
    {
        $this->considerSymbolsAsLitterals = $considerSymbolsAsLitterals;
    }

    /**
     * @inheritDoc
     */
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormaterInterface $formater)
    {
        if (
            $token->isLitteral()
            || $this->considerSymbolsAsLitterals && $token->isSymbol()
        ) {
            return $token->getValue();
        }
    }
}
