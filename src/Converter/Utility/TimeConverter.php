<?php

namespace Popy\Calendar\Converter\Utility;

class TimeConverter
{
    /**
     * Converts a "Time" (represented by an array of each of its constituents)
     *     from one format (defined by constituents sizes) into another format
     *     (defined by constituents sizes) using a "lower unity count ratio"
     *     calculation.
     *
     * @param array<int> $timeParts           Time constituents array.
     * @param array<int> $sourceFractionSizes Source time constituants ranges.
     * @param array<int> $targetFractionSizes Target time constituants ranges.
     *
     * @return array<int>
     */
    public function convertTime(array $timeParts, array $sourceFractionSizes, array $targetFractionSizes)
    {
        $count = $this->getLowerUnityCountFromTime($timeParts, $sourceFractionSizes);

        // Now we multiply the count by the total units per day of the target
        //    format, then divide by the total units per date of the source
        //    format, so we only have one division, one transformation to float,
        //    so only one "float imprecision" issue.
        $count = ($count * array_product($targetFractionSizes))
            / array_product($targetFractionSizes)
        ;

        return $this->getTimeFromLowerUnityCount($count, $targetFractionSizes);
    }

    /**
     * Converts a "Time" (represented by an array of each of its constituents)
     *     into the lowest of its defined units (usefull if you want, for
     *     instance, to convert a [h,m,s,u] into seconds)
     *
     * @param array<int> $timeParts     Time constituents array.
     * @param array<int> $fractionSizes Time constituants ranges.
     *
     * @return integer
     */
    public function getLowerUnityCountFromTime(array $timeParts, array $fractionSizes)
    {
        $len = count($fractionSizes);
        $res = 0;
   
        for ($i=0; $i < $len; $i++) {
            $part = isset($timeParts[$i]) ? $timeParts[$i] : 0;
            $res = $res * $fractionSizes[$i] + $part;
        }

        return $res;
    }

    /**
     * Convert a "lowest unity count" time representation to an array of
     *     constituents.
     *
     * @param integer $count         Lowest unity count.
     * @param array   $fractionSizes Time constituants ranges.
     *
     * @return array
     */
    public function getTimeFromLowerUnityCount($count, array $fractionSizes)
    {
        $len = count($fractionSizes);
        $res = array_fill(0, $len, 0);

        for ($i=$len - 1; $i > -1 ; $i--) {
            $res[$i] = $count % $fractionSizes[$i];
            $count = intval($count / $fractionSizes[$i]);
        }

        return $res;
    }
}
