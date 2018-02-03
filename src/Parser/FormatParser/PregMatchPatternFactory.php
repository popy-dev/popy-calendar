<?php

namespace Popy\Calendar\Parser\FormatParser;

use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\Parser\FormatLexer\MbString;
use Popy\Calendar\Parser\FormatParserInterface;
use Popy\Calendar\Parser\DateLexer\PregMatchPattern;

class PregMatchPatternFactory implements FormatParserInterface
{
    public function __construct()
    {
        $this->lexer = new MbString();
        $this->symbolParser = new SymbolParser();
    }

    /**
     * @inheritDoc
     */
    public function parseFormat($format)
    {
        $tokens = $this->lexer->tokenize($format);

        $dateParser = new PregMatchPattern();

        foreach ($tokens as $token) {
            if ($token->is('|')) {
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

            if ($token->isLitteral()) {
                $dateParser->register($token->getSymbol());
                continue;
            }

            $dateParser->register($pattern, $token->getSymbol());
        }

        $dateParser->close();
        $dateParser->compile();

        return $dateParser;
    }
}