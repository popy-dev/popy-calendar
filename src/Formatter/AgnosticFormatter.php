<?php

namespace Popy\Calendar\Formatter;

use DateTimeInterface;
use Popy\Calendar\FormatterInterface;
use Popy\Calendar\ConverterInterface;
use Popy\Calendar\Parser\FormatLexerInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateRepresentation\Date;

/**
 * Agnostic formatter implementation.
 */
class AgnosticFormatter implements FormatterInterface
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
     * Symbol Formatter
     *
     * @var SymbolFormatterInterface
     */
    protected $formatter;

    /**
     * Class constructor.
     *
     * @param FormatLexerInterface    $lexer     Format lexer.
     * @param ConverterInterface      $converter Date converter.
     * @param SymbolFormatterInterface $formatter  Symbol formatter.
     */
    public function __construct(FormatLexerInterface $lexer, ConverterInterface $converter, SymbolFormatterInterface $formatter)
    {
        $this->lexer     = $lexer;
        $this->converter = $converter;
        $this->formatter  = $formatter;
    }

    /**
     * @inheritDoc
     */
    public function format(DateTimeInterface $input, $format)
    {
        $date = Date::buildFromDateTimeInterface($input);

        if (null === $date = $this->converter->to($date)) {
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
            $res .= $this->formatter->formatSymbol($input, $token, $this);
        }

        return $res;
    }
}
