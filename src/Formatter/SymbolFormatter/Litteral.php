<?php

namespace Popy\Calendar\Formatter\SymbolFormatter;

use Popy\Calendar\FormatterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formatter\SymbolFormatterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Handles Litteral tokens.
 */
class Litteral implements SymbolFormatterInterface
{
    /**
     * Will consider symbols (not handled by previous formatters) as litterals
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
    public function formatSymbol(DateRepresentationInterface $input, FormatToken $token, FormatterInterface $formatter)
    {
        if (
            $token->isLitteral()
            || $this->considerSymbolsAsLitterals && $token->isSymbol()
        ) {
            return $token->getValue();
        }
    }
}
