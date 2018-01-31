<?php

namespace Popy\Calendar\Parser;

use Popy\Calendar\ParserInterface;

class NullParser implements ParserInterface
{
    /**
     * @inheritDoc
     */
    public function parse($input, $format)
    {
        return null;
    }
}
