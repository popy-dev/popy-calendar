<?php

namespace Popy\Calendar\Formater;

/**
 * Formater helper trait.
 */
trait FormatLexerTrait
{
    /**
     * Format an input date by reading and formatting the input $format character by character.
     *
     * @param mixed  $input  Input date, intentionnaly not typehinted so anything can be used.
     * @param string $format Format.
     *
     * @return string
     */
    public function doFormat($input, $format)
    {
        $res = '';
        $escaped = false;
        $symbols = str_split($format, 1);

        foreach ($symbols as $symbol) {
            if ($escaped) {
                $escaped = false;
                $res .= $symbol;
                continue;
            }

            if ($symbol === '\\') {
                $escaped = true;
                continue;
            }

            $res = $this->formatSymbol($res, $input, $symbol);
        }

        return $res;
    }

    /**
     * Format a single symbol.
     *
     * @param string $res    Accumulated formatted symbols.
     * @param mixed  $input  Date input.
     * @param string $symbol Symbol to format.
     * 
     * @return string The new $res
     */
    abstract protected function formatSymbol($res, $input, $symbol);
}
