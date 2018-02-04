<?php

namespace Popy\Calendar\Parser\FormatParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\Parser\FormatLexer\MbString;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\SymbolParserInterface;
use Popy\Calendar\Parser\DateLexer\PregSimple;
use Popy\Calendar\Parser\DateLexer\PregCollection;
use Popy\Calendar\Parser\SymbolParser\NativeFormatPregMatch;

/**
 * Preg based implementation of the native DateTimeInterface format, with an
 * "extension" : | symbols delimits format alternatives.
 *
 * Uses PregCollection date lexer, so is only compatible with SymbolParsers that
 * returns Preg lexers. Otherwise, use another parser, like the basic one.
 *
 * PregCollection is usually 3x faster than a standard Collection lexer.
 */
class PregExtendedNative implements FormatParserInterface
{
    /**
     * Class constructor.
     *
     * @param FormatLexerInterface|null  $lexer        Format lexer.
     * @param SymbolParserInterface|null $symbolParser Symbol Parser
     */
    public function __construct(FormatLexerInterface $lexer = null, SymbolParserInterface $symbolParser = null)
    {
        $this->lexer = $lexer ?: new MbString();
        $this->symbolParser = $symbolParser ?: new NativeFormatPregMatch();
    }

    /**
     * @inheritDoc
     */
    public function parseFormat($format)
    {
        $tokens = $this->lexer->tokenizeFormat($format);

        $dateParser = new PregCollection();

        $eofLexer = new PregSimple(new FormatToken(null, FormatToken::TYPE_EOF));

        foreach ($tokens as $token) {
            if ($token->is('|')) {
                $dateParser->register($eofLexer);
                $dateParser->close();
                continue;
            }

            $lexer = null;

            if (
                $token->isSymbol()
                && null === $lexer = $this->symbolParser->parseSymbol(
                    $token,
                    $this
                )
            ) {
                // finally, token seems litteral
                $token = $token->setLitteral();
            }

            if ($lexer === null) {
                $lexer = new PregSimple($token);
            }

            $dateParser->register($lexer);
        }

        $dateParser->close();
        $dateParser->compile();

        return $dateParser;
    }
}
