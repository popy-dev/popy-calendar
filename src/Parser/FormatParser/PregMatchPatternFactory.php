<?php

namespace Popy\Calendar\Parser\FormatParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\Parser\FormatLexer\MbString;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\SimplePreg;
use Popy\Calendar\Parser\DateLexer\PregMatchPattern;
use Popy\Calendar\Parser\SymbolParser\NativeFormatPregMatch;

/**
 * PREG based parser.
 *
 * Handles a special | character.
 */
class PregMatchPatternFactory implements FormatParserInterface
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

        $dateParser = new PregMatchPattern();

        $eof = new FormatToken(null, FormatToken::TYPE_EOF);
        $eofLexer = new SimplePreg($eof);

        foreach ($tokens as $token) {
            if ($token->is('|')) {
                $dateParser->register($eof, $eofLexer);
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
                $lexer = new SimplePreg($token);
            }

            $dateParser->register($token, $lexer);
        }

        $dateParser->close();
        $dateParser->compile();

        return $dateParser;
    }
}
