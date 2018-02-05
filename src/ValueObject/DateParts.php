<?php

namespace Popy\Calendar\ValueObject;

/**
 * A DateParts is a fragmented representation of a time period of variable
 * length (like gregorian monthes). It is an Immutable object.
 *
 * A DateParts MAY not be aware of all|some of its fragments. (usually when it's
 * been built by a parser).
 *
 * A DateParts is not aware of the time format it holds, and MUST NOT check for
 * internal consistency. It is only a value holder, and calculation has to be
 * made by converters, or other components.
 */
class DateParts extends AbstractFragmentedDuration
{
}
