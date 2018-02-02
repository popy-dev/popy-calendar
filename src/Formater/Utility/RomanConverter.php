<?php

namespace Popy\Calendar\Formater\Utility;

/**
 * Roman units converter.
 */
class RomanConverter
{
    /**
     * Converts an integer to its roman version.
     * 
     * @param integer $input
     *
     * @return string
     */
    public function decimalToRoman($input) 
    { 
        $table = array(
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
        ); 
        $res = '';

        foreach ($table as $symbol => $value) {
            while ($input >= $value) {
                $res .= $symbol;
                $input -= $value;
            }
        }

        return $res;
    } 

}