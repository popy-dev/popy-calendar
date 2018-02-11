<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Formater\NumberConverterInterface;

/**
 * Implementation of the native DateTime year formats using preg lexers.
 */
class PregNativeDateSolar implements SymbolParserInterface
{
    /**
     * Number converter.
     *
     * @var NumberConverterInterface
     */
    protected $converter;

    /**
     * Class constructor.
     *
     * @param NumberConverterInterface $converter Number converter.]
     */
    public function __construct(NumberConverterInterface $converter)
    {
        $this->converter = $converter;
    }

    /**
     * @inheritDoc
     */
    public function parseSymbol(FormatToken $token, FormatParserInterface $parser)
    {
        if ($token->is('y')) {
            $converter = $this->converter;
            
            // y   A two digit representation of a year
            $lexer = new PregSimple($token, '\d\d');
            $lexer->setCallback(function (PregSimple $lexer, $value) use ($converter) {
                return $converter->from($value);
            });

            return $lexer;
        }

        if ($token->isOne('Y')) {
            // Y   A full numeric representation of a year, 4 digits
            return new PregSimple($token, '\d\d\d\d');
        }

        if ($token->is('L')) {
            // L   Whether it's a leap year
            return new PregSimple($token, '[01]');
        }

        if ($token->is('z')) {
            // z   The day of the year (starting from 0)
            return new PregSimple($token, '\d{1,3}');
        }
    }
}
