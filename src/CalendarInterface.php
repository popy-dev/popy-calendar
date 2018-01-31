<?php

namespace Popy\Calendar;

/**
 * A calendar is a toolbox for manipulating (formatting, parsing) dates into/from
 * various ways of representing a date.
 *
 * The dates which will be manipulated will be typehinted DateTimeInterface. While
 * it would be preferable to only use DateTimeImmutable, DateTimeInterface allows
 * for greater compatibility with already existing code.
 */
interface CalendarInterface extends FormaterInterface, ParserInterface
{

}
