<?php

namespace Popy\Calendar\Formatter\NumberConverter;

use Popy\Calendar\Formatter\NumberConverterInterface;

/**
 * Converts years for RFC2550 Implementation.
 *
 * @link https://tools.ietf.org/html/rfc2550
 */
class RFC2550 implements NumberConverterInterface
{
    const ORD_START = 65;
    const POSITIVE_CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ^';
    const NEGATIVE_CHARS = '9876543210ZYXWVUTSRQPONMLKJIHGFEDCBA!';

    /**
     * @inheritDoc
     */
    public function to($input)
    {
        $input = (string)$input;

        if ($input[0] === '-' || preg_match('/^0+$/', $input)) {
            return $this->convertNegativeTo($input);
        }

        return $this->convertPositiveTo($input);
    }

    /**
     * Convert a positive value into its RFC2550 representation.
     *
     * @param string $input Value (as a string)
     *
     * @return string
     */
    protected function convertPositiveTo($input)
    {
        $extraLen = strlen($input) - 5;

        if ($extraLen < 0) {
            return str_pad($input, 4, '0', STR_PAD_LEFT);
        }

        if ($extraLen < 26) {
            return chr(static::ORD_START + $extraLen) . $input;
        }

        $extraLen -= 25;

        $prefix = '';
        $carets = '';

        do {
            $carets .= '^';
            $extraLen--;

            $prefix = chr(static::ORD_START + $extraLen % 26) . $prefix;
            $extraLen = intval($extraLen / 26);
        } while ($extraLen);

        return "${carets}${prefix}${input}";
    }

    /**
     * Converts a negative value into its RFC2550 representation.
     *
     * @param string $input Value (as a string)
     *
     * @return string
     */
    protected function convertNegativeTo($input)
    {
        $res = $this->convertPositiveTo(substr($input, 1));

        $res = strtr($res, static::POSITIVE_CHARS, static::NEGATIVE_CHARS);

        if (strlen($res) < 5) {
            $res = '/' . $res;
        } elseif ($res[0] !== '!') {
            $res = '*' . $res;
        }

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function from($input)
    {
        if (strpos('!/*', $input[0]) !== false) {
            return $this->convertFromNegative($input);
        }

        return $this->convertFromPositive($input);
    }

    /**
     * Convert a positive representation.
     *
     * @param string $input
     *
     * @return string|null
     */
    public function convertFromPositive($input)
    {
        $matches = [];

        if (
            !preg_match(
                '
                /^0*(?<carets>\\^*)(?<alpha>[A-Z]*)(?<year>\d+)$/',
                $input,
                $matches
            )
        ) {
            return null;
        }

        if ($matches['alpha'] === '') {
            return $matches['year'];
        }

        if ($matches['carets'] === '') {
            $expectedLength = ord($matches['alpha']) - static::ORD_START + 5;
        } else {
            $len = strlen($matches['carets']);

            $expectedLength = 0;

            for ($i=0; $i < $len; $i++) {
                $expectedLength = $expectedLength * 26;
                $expectedLength += ord($matches['alpha'][$i]) - static::ORD_START;
                $expectedLength++;
            }

            $expectedLength += 30;
        }

        return str_pad($matches['year'], $expectedLength, '0', STR_PAD_RIGHT);
    }

    /**
     * Convert a negative representation.
     *
     * @param string $input
     *
     * @return string|null
     */
    public function convertFromNegative($input)
    {
        if (strpos('/*', $input[0]) !== false) {
            $input = substr($input, 1);
        }

        $input = strtr($input, static::NEGATIVE_CHARS, static::POSITIVE_CHARS);

        $res = $this->convertFromPositive($input);

        if (preg_match('/^0+$/', $res)) {
            return $res;
        }

        return '-' . $res;
    }
}
