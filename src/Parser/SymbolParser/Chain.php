<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Parser\DateLexer\PregChoice;
use Popy\Calendar\Formater\LocalisationInterface;
use Popy\Calendar\Formater\Localisation\NativeHardcoded;

/**
 * Chain implementation, stopping at the first result
 */
class Chain implements SymbolParserInterface
{
    /**
     * Parser chain.
     *
     * @var array<SymbolParserInterface>
     */
    protected $parsers = [];
    
    /**
     * Class constructor.
     *
     * @param iterable<SymbolParserInterface> $parsers Parser chain.
     */
    public function __construct($parsers = [])
    {
        $this->addParsers($parsers);
    }
    
    /**
     * Adds a Parser to the chain.
     *
     * @param SymbolParserInterface $parser
     */
    public function addParser(SymbolParserInterface $parser)
    {
        if ($parser instanceof self) {
            // Reducing recursivity
            $this->addParsers($parser->parsers);
        } else {
            $this->parsers[] = $parser;
        }
    
        return $this;
    }
    
    /**
     * Add parsers to the chain.
     *
     * @param iterable<SymbolParserInterface> $parsers
     */
    public function addParsers($parsers)
    {
        foreach ($parsers as $parser) {
            $this->addParser($parser);
        }
    
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $parser)
    {
        foreach ($this->parsers as $p) {
            if (null !== $res = $p->parseSymbol($token, $parser)) {
                return $res;
            }
        }
    }
}
