<?php

namespace Popy\Calendar\Parser\FormatParser;

use Popy\Calendar\Parser\FormatToken;
use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\Parser\FormatLexer\MbString;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregMatchPattern;
use Popy\Calendar\Parser\SymbolParser\NativeFormatPregMatch;

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

        foreach ($tokens as $token) {
            if ($token->is('|')) {
                $dateParser->register(
                    new FormatToken(null, FormatToken::TYPE_EOF)
                );
                $dateParser->close();
                continue;
            }

            if (
                !$token->isLitteral()
                && null === $pattern = $this->symbolParser->parseSymbol(
                    $token,
                    $this
                )
            ) {
                // finally, token seems litteral
                $token = $token->setLitteral();
            }

            $dateParser->register($token, $pattern);
        }

        $dateParser->register(
            new FormatToken(null, FormatToken::TYPE_EOF)
        );
        $dateParser->close();
        $dateParser->compile();

        return $dateParser;
    }
}