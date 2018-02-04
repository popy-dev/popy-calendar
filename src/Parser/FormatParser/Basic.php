<?php

namespace Popy\Calendar\Parser\FormatParser;

use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\Parser\FormatLexer\MbString;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\Collection;
use Popy\Calendar\Parser\DateLexer\NonSymbolMatcher;
use Popy\Calendar\Parser\SymbolParser\NativeFormatPregMatch;

/**
 * Basic implementation : builds a Collection DateLexer containing every lexer
 * built by the SymbolParser.
 *
 * The results are highly dependent on the quality of the Lexers provided by the
 * SymbolParser, as everything fails on the first error, while some lexers could
 * try matching more characters.
 */
class Basic implements FormatParserInterface
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

        $dateParser = new Collection();

        foreach ($tokens as $token) {
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
                $lexer = new NonSymbolMatcher($token);
            }

            $dateParser->addLexer($lexer);
        }

        return $dateParser;
    }
}
