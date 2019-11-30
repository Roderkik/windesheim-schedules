<?php


namespace App\Helpers;

use Carbon\Carbon;
use DateTime;
use Liliumdev\ICalendar\ZCiCal;
use Liliumdev\ICalendar\ZCiCalDataNode;
use Liliumdev\ICalendar\ZCiCalNode;

class CalendarEvent
{
    private ZCiCalNode $event;

    public function __construct(ZCiCal $calendar)
    {
        $this->event = new ZCiCalNode("VEVENT", $calendar->curnode);

        $this->addNode("UID:" . md5(time() * mt_rand()));
    }

    public function title(string $title): CalendarEvent
    {
        $this->addNode("SUMMARY:" . $title);

        return $this;
    }

    public function start(string $start, string $timezone = null): CalendarEvent
    {
        $this->addDateNode("DTSTART", $start, $timezone);

        return $this;
    }

    public function end(string $end, string $timezone = null): CalendarEvent
    {
        $this->addDateNode("DTEND", $end, $timezone);

        return $this;
    }

    public function stamp(string $timezone = null): CalendarEvent
    {
        $this->addDateNode("DTSTAMP", CalendarEvent::icalFormat(Carbon::now()), $timezone);

        return $this;
    }

    public function description(string $end): CalendarEvent
    {
        $this->addNode("DESCRIPTION:" . $end);

        return $this;
    }

    public function location(string $location): CalendarEvent
    {
        $this->addNode("LOCATION:" . $location);

        return $this;
    }

    public function addCustomNode(ZCiCalDataNode $node): CalendarEvent
    {
        $this->event->addNode($node);

        return $this;
    }

    private function addNode(string $nodeString): void
    {
        $this->event->addNode(new ZCiCalDataNode($nodeString));
    }

    private function addDateNode(string $type, string $time, string $timezone = null): void
    {
        if (empty($timezone)) {
            $this->addNode("$type:" . $time);
        } else {
            $this->addNode("$type;" . "TZID=$timezone:" . $time);
        }
    }

    /**
     * Formats a DateTime/Carbon instance to a correct ical format
     * https://www.kanzaki.com/docs/ical/dateTime.html
     *
     * @param DateTime|Carbon $date
     * @param bool $utc
     * @return string
     */
    public static function icalFormat($date, bool $utc = false): string
    {
        if ($date instanceof DateTime) {
            $date = Carbon::instance($date);
        }

        $formatted =
            self::padDateComponent($date->year) .
            self::padDateComponent($date->month) .
            self::padDateComponent($date->day) .
            "T" .
            self::padDateComponent($date->hour) .
            self::padDateComponent($date->minute) .
            self::padDateComponent($date->second);
        if ($utc) {
            $formatted .= "Z";
        }

        return $formatted;
    }

    /**
     * Takes the month, day, hour, minute or second
     * represented as an integer and prepends
     * a zero to it if it's less
     * than 10.
     *
     * method signature could be improved...
     *
     * @param int $dateComponent
     * @return string
     */
    private static function padDateComponent(int $dateComponent): string
    {
        if ($dateComponent < 10) {
            return str_pad($dateComponent, 2, '0', STR_PAD_LEFT);
        }

        return (string) $dateComponent;
    }

}
