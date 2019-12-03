<?php


namespace App\Helpers;

use Carbon\Carbon;
use InvalidArgumentException;
use Liliumdev\ICalendar\ZCiCal;
use Liliumdev\ICalendar\ZCiCalDataNode;
use Liliumdev\ICalendar\ZCiCalNode;

class Event
{
    private ZCiCalNode $event;
    private array $nodes;

    public function __construct()
    {
        $this->nodes = [];
    }

    public function build(ZCiCal $calendar)
    {
        $this->event = new ZCiCalNode("VEVENT", $calendar->curnode);
        $this->nodes[] = ["type" => "standard", "name" => "UID:", "value" => md5(time() * mt_rand())];

        foreach ($this->nodes as $node) {
            switch ($node["type"]) {
                case "standard":
                    $this->event->addNode(new ZCiCalDataNode($node["name"] . $node["value"]));
                    break;
                case "date":
                    $this->event->addNode($this->addDateNode($node));
                    break;
                default:
                    throw new InvalidArgumentException("$node must contain field \"type\"!");
                    break;
            }
        }
    }

    private function addDateNode(array $node): ZCiCalDataNode
    {
        $name = $node["name"];
        $time = $node["value"];
        $timezone = $node["timezone"];

        if (is_null($timezone)) {
            $dataNode = new ZCiCalDataNode("$name:" . $time);
        } else {
            $dataNode = new ZCiCalDataNode("$name;" . "TZID=$timezone:" . $time);
        }

        return $dataNode;
    }

    /**
     * Formats a DateTime/Carbon instance to a correct ical format
     * https://www.kanzaki.com/docs/ical/dateTime.html
     *
     * @param Carbon $date
     * @param bool $utc
     * @return string
     */
    public static function icalFormat(Carbon $date, bool $utc = false): string
    {
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
     * method naming could be improved...
     *
     * @param int $dateComponent
     * @return string
     */
    private static function padDateComponent(int $dateComponent): string
    {
        if ($dateComponent < 10) {
            return str_pad($dateComponent, 2, '0', STR_PAD_LEFT);
        }

        return (string)$dateComponent;
    }

    public function start(Carbon $start, string $timezone = null): Event
    {
        $this->nodes[] = [
            "type" => "date",
            "name" => "DTSTART",
            "value" => Event::icalFormat($start),
            "timezone" => $timezone
        ];

        return $this;
    }

    public function end(Carbon $end, string $timezone = null): Event
    {
        $this->nodes[] = [
            "type" => "date",
            "name" => "DTEND",
            "value" => Event::icalFormat($end),
            "timezone" => $timezone
        ];

        return $this;
    }

    public function stamp(string $timezone = null): Event
    {
        $this->nodes[] = [
            "type" => "date",
            "name" => "DTSTAMP",
            "value" => Event::icalFormat(Carbon::now()),
            "timezone" => $timezone
        ];

        return $this;
    }

    public function title(string $title): Event
    {
        $this->addNode("SUMMARY:", $title);

        return $this;
    }

    private function addNode(string $name, string $value): void
    {
        $this->nodes[] = ["type" => "standard", "name" => $name, "value" => $value];
    }

    public function description(string $description): Event
    {
        $this->addNode("DESCRIPTION:", $description);

        return $this;
    }

    public function location(string $location): Event
    {
        $this->addNode("LOCATION:", $location);

        return $this;
    }

    public function customNode(string $name, string $value): Event
    {
        $this->addNode($name, $value);

        return $this;
    }

}
