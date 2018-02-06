<?php

namespace Popy\Calendar\Formater;

use DateTimeInterface;
use Popy\Calendar\FormaterInterface;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;

/**
 * Agnostic formater implementation.
 */
class AgnosticFormater implements FormaterInterface
{
    /**
     * Format lexer
     *
     * @var FormatLexerInterface
     */
    protected $lexer;

    /**
     * Date converter.
     *
     * @var ConverterInterface
     */
    protected $converter;

    /**
     * Symbol Formater
     *
     * @var SymbolFormaterInterface
     */
    protected $formater;

    /**
     * Class constructor.
     *
     * @param FormatLexerInterface    $lexer     Format lexer.
     * @param ConverterInterface      $converter Date converter.
     * @param SymbolFormaterInterface $formater  Symbol formater.
     */
    public function __construct(FormatLexerInterface $lexer, ConverterInterface $converter, SymbolFormaterInterface $formater)
    {
        $this->lexer     = $lexer;
        $this->converter = $converter;
        $this->formater  = $formater;
    }

    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        if (null === $date = $this->converter->fromDateTimeInterface($input)) {
            return;
        }
        return $this->formatDateRepresentation($date, $format);
    }

    /**
     * @inheritDoc
     */
    public function formatDateRepresentation(DateRepresentationInterface $input, $format)
    {
        $res = '';
        $tokens = $this->lexer->tokenizeFormat($format);

        foreach ($tokens as $token) {
            $res .= $this->formater->formatSymbol($input, $token, $this);
        }

        return $res;
    }
}
