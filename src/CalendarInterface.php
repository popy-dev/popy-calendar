<?php

namespace Popy\Calendar;

/**
 * A calendar is a toolbox for manipulating (formatting, parsing) dates into/from
 * various ways of representing a date.
 */
interface CalendarInterface extends FormaterInterface, ParserInterface
{
}
