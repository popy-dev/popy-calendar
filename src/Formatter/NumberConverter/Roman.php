<?php

namespace Popy\Calendar\Formatter\NumberConverter;

use Popy\Calendar\Formatter\NumberConverterInterface;

/**
 * Roman units converter.
 */
class Roman implements NumberConverterInterface
{
    protected static $table = [
        'M'  => 1000,
        'CM' => 900,
        'D'  => 500,
        'CD' => 400,
        'C'  => 100,
        'XC' => 90,
        'L'  => 50,
        'XL' => 40,
        'X'  => 10,
        'IX' => 9,
        'V'  => 5,
        'IV' => 4,
        'I'  => 1,
    ];

    /**
     * @inheritDoc
     */
    public function to($input)
    {
        if ($input === 0) {
            return '0';
        }

        if ($input < 0) {
            return '-' . $this->to(-$input);
        }
        
        $res = '';

        foreach (self::$table as $symbol => $value) {
            while ($input >= $value) {
                $res .= $symbol;
                $input -= $value;
            }
        }

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function from($input)
    {
        if ($input === '0') {
            return 0;
        }

        $res = $i = 0;
        $len = strlen($input);
        $sign = 1;

        if (substr($input, 0, 1) === '-') {
            $sign = -1;
            $i = 1;
        }

        while ($i < $len) {
            foreach (static::$table as $symbol => $value) {
                $sl = strlen($symbol);

                if ($symbol === substr($input, $i, $sl)) {
                    $res += $value;
                    $i += $sl;

                    continue 2;
                }
            }

            // If nothing matched, exit.
            return null;
        }

        return $sign * $res;
    }
}
