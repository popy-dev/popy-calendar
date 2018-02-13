<?php

namespace Popy\Calendar\Parser\SymbolParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Formatter\NumberConverterInterface;

/**
 * Matches the native 'y' symbol when it contains a RFC2550 year.
 */
class PregNativeRFC2550 implements SymbolParserInterface
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
            $lexer = new PregSimple($token, '[\\/*]?[!^]*[A-Z]*\d+');
            $lexer->setCallback(function (PregSimple $lexer, $value) use ($converter) {
                return $converter->from($value);
            });

            return $lexer;
        }
    }
}
