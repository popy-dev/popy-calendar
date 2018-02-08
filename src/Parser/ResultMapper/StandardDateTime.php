<?php

namespace Popy\Calendar\Parser\ResultMapper;

use Popy\Calendar\Parser\DateLexerResult;
use Popy\Calendar\Parser\ResultMapperInterface;
use Popy\Calendar\ValueObject\DateRepresentationInterface;
use Popy\Calendar\ValueObject\DateTimeRepresentationInterface;

/**
 * Maps standard format symbols to DateTimeRepresentationInterface fields.
 */
class StandardDateTime implements ResultMapperInterface
{
    /**
     * @inheritDoc
     */
    public function map(DateLexerResult $parts, DateRepresentationInterface $date)
    {
        if (!$date instanceof DateTimeRepresentationInterface) {
            return;
        }

        // g   12-hour format of an hour without leading zeros 1 through 12
        // h   12-hour format of an hour with leading zeros    01 through 12
        // G   24-hour format of an hour without leading zeros 0 through 23
        // H   24-hour format of an hour with leading zeros    00 through 23
        // i   Minutes with leading zeros  00 to 59
        // s   Seconds, with leading zeros 00 through 59
        // v   Milliseconds
        // μ   Microseconds (the u microseconds is used for SI microseconds)
        $time = $date
            ->getTime()
            ->withFragments([
                $this->intIfNotNull($parts->getFirst('g', 'G', 'h', 'H')),
                $this->intIfNotNull($parts->get('i')),
                $this->intIfNotNull($parts->get('s')),
                $this->intIfNotNull($parts->get('v')),
                $this->intIfNotNull($parts->get('μ')),
            ])
            ->withHalved(0, $this->determineAmPm($parts))
        ;

        // B   Swatch Internet time    000 through 999
        if (null !== $b = $parts->get('B')) {
            $time = $time->withRatio((int)$b * 1000);
        }

        return $date->withTime($time);
    }

    public function intIfNotNull($value)
    {
        return $value === null ? null : (int)$value;
    }

    protected function determineAmPm(DateLexerResult $parts)
    {
        // a   Lowercase Ante meridiem and Post meridiem   am or pm
        // A   Uppercase Ante meridiem and Post meridiem   AM or PM
        if (null === $str = $parts->getFirst('a', 'A')) {
            return;
        }
        return strtolower($str) === 'pm';
    }
}
