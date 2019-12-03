<?php


namespace App\Helpers;


use Liliumdev\ICalendar\ZCiCal;
use Liliumdev\ICalendar\ZCTimeZoneHelper;

class Calendar
{
    private const POST_EXPORT_INJECT_NODE = "METHOD:PUBLISH";
    private ZCiCal $calendar;
    private array $events;
    private array $customNodes;

    public function __construct()
    {
        $this->calendar = new ZCiCal();
        $this->events = [];
        $this->customNodes = [];
    }

    /**
     * Adds an event to the calendar.
     *
     * @param CalendarEvent $event
     * @return Calendar
     */
    public function event(CalendarEvent $event): Calendar
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * Wrapper of ZCTimeZondeHelper::getTZNode
     *
     * @param int $startYear start year of date range
     * @param int $endYear end year of date range
     * @param string $timezoneId a valid PHP timezone
     * @return Calendar
     */
    public function timezoneNode(int $startYear, int $endYear, string $timezoneId): Calendar
    {
        ZCTimeZoneHelper::getTZNode($startYear, $endYear, $timezoneId, $this->calendar->curnode);

        return $this;
    }

    /**
     * Adds a custom node to the calendar, these are used
     * by calendar programs as custom properties.
     *
     * @param string $node a custom node, example: X-WR-TIMEZONE:Europe/Amsterdam
     * @param string $afterNode the node that $node will be inserted after
     * @return Calendar
     */
    public function customNode(string $node, string $afterNode = self::POST_EXPORT_INJECT_NODE): Calendar
    {
        $this->customNodes[] = [
            "node" => $node,
            "after" => $afterNode
        ];

        return $this;
    }

    /**
     * Wrapper around ZCiCal::export
     *
     * Builds all CalendarEvent into $calendar.
     * Inserts custom nodes if available.
     *
     * @return string
     */
    public function export(): string
    {
        foreach ($this->events as $event) {
            $event->build($this->calendar);
        }

        $stream = $this->calendar->export();

        if (!empty($this->customNodes)) {
            foreach ($this->customNodes as $customNode) {
                $stream = Calendar::insertNodeIntoStream($stream, $customNode["node"], $customNode["after"]);
            }
        }

        return $stream;
    }

    /**
     * Inserts a node into the passed stream.
     *
     * @param string $stream
     * @param string $node
     * @param string $afterNode
     * @return string
     */
    private static function insertNodeIntoStream(string $stream, string $node, string $afterNode): string
    {
        return substr_replace(
            $stream,
            "$node\r\n",
            strpos($stream, $afterNode),
            0
        );
    }
}
