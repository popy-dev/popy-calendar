<?php

namespace Popy\Calendar\Formatter\SymbolFormatter;

use Popy\Calendar\FormatterInterface;
use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Formatter\SymbolFormatterInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Chain/Collection implementation. Will delegate to internal formatters until
 * one of them returns a result.
 */
class Chain implements SymbolFormatterInterface
{
    /**
     * Formatter chain.
     *
     * @var array<SymbolFormatterInterface>
     */
    protected $formatters = [];

    /**
     * Class constructor.
     *
     * @param iterable<SymbolFormatterInterface> $formatters
     */
    public function __construct($formatters = [])
    {
        $this->addFormatters($formatters);
    }

    /**
     * Adds a formatter to the chain.
     *
     * @param SymbolFormatterInterface $formatter
     */
    public function addFormatter(SymbolFormatterInterface $formatter)
    {
        if ($formatter instanceof self) {
            // Reducing recursivity
            $this->addFormatters($formatter->formatters);
        } else {
            $this->formatters[] = $formatter;
        }

        return $this;
    }

    /**
     * Add formatters to the chain.
     *
     * @param iterable<SymbolFormatterInterface> $formatters
     */
    public function addFormatters($formatters)
    {
        foreach ($formatters as $formatter) {
            $this->addFormatter($formatter);
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function formatSymbol(
        DateRepresentationInterface $input,
        FormatToken $token,
        FormatterInterface $formatter
    ) {
        foreach ($this->formatters as $f) {
            if (null !== $res = $f->formatSymbol($input, $token, $formatter)) {
                return $res;
            }
        }
    }
}
