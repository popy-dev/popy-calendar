<?php

namespace Popy\Calendar\Formater\Utility;

/**
 * Roman units converter.
 */
class RomanConverter
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
     * Converts an integer to its roman version.
     *
     * @param integer $input
     *
     * @return string
     */
    public function decimalToRoman($input)
    {
        if ($input < 0) {
            return '-' . $this->decimalToRoman(-$input);
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
     * Converts a roman number to a decimal one.
     *
     * @param string $input
     * 
     * @return integer
     */
    public function romanToDecimal($input)
    {
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
