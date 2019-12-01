<?php

namespace App\Http\Controllers;

use App\Helpers\CalendarEvent;
use App\Helpers\WindesheimApi;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Liliumdev\ICalendar\ZCiCal;
use Liliumdev\ICalendar\ZCTimeZoneHelper;

class CalendarController extends Controller
{
    private WindesheimApi $api;
    private ZCiCal $calendar;

    /**
     * Initializes controller properties.
     */
    public function __construct()
    {
        $this->api = new WindesheimApi();
        $this->calendar = new ZCiCal();
    }

    /**
     * Generate ical file for the schedule of a class
     *
     * @param string $class
     * @return Response
     */
    public function class(string $class): Response
    {
        $this->api->setClass($class);
        $this->buildCalendar();

        return $this->calendarResponse($class);
    }

    /**
     * Generate ical file for the schedule of a teacher
     *
     * @param string $teacher
     * @return Response
     */
    public function teacher(string $teacher): Response
    {
        $this->api->setTeacher($teacher);
        $this->buildCalendar();

        return $this->calendarResponse($teacher);
    }

    /**
     * Generate ical file for the schedule of a subject
     *
     * @param string $subject
     * @return Response
     */
    public function subject(string $subject): Response
    {
        $this->api->setSubject($subject);
        $this->buildCalendar();

        return $this->calendarResponse($subject);
    }

    /**
     * Names the calendar and adds
     * events found in api.
     *
     * @return void
     */
    private function buildCalendar(): void
    {
        ZCTimeZoneHelper::getTZNode(
            Carbon::now()->year,
            Carbon::now()->year,
            "Europe/Amsterdam",
            $this->calendar->curnode
        );

        foreach ($this->api->schedule() as $scheduleDatum) {
            if (empty($scheduleDatum->vaknaam) || empty($scheduleDatum->vakcode)) {
                $scheduleDatum->vaknaam = $scheduleDatum->commentaar;
                $scheduleDatum->vakcode = $scheduleDatum->commentaar;
            }

            // Transform time to UTC
            $scheduleDatum->starttijd = Carbon::createFromTimestampMs($scheduleDatum->starttijd);
            $scheduleDatum->eindtijd = Carbon::createFromTimestampMs($scheduleDatum->eindtijd);

            $event = new CalendarEvent($this->calendar);
            $event->title($scheduleDatum->commentaar);
            $event->location($scheduleDatum->lokaal);
            $event->start(CalendarEvent::icalFormat($scheduleDatum->starttijd), "Europe/Amsterdam");
            $event->end(CalendarEvent::icalFormat($scheduleDatum->eindtijd), "Europe/Amsterdam");
            $event->stamp("Europe/Amsterdam");
        }
    }

    /**
     * Creates the standard response
     * used by this controller.
     *
     * @param string $name
     * @return Response
     */
    private function calendarResponse(string $name): Response
    {
        //Add X-WR-TIMEZONE, X-WR-CALNAME & NAME post export because liliumdev/icalendar doesn't support it
        $stream = $this->calendar->export();
        $needle = "METHOD:PUBLISH";

        $stream = substr_replace(
            $stream,
            "X-WR-TIMEZONE:Europe/Amsterdam\r\nX-WR-CALNAME:$name\r\nNAME:$name\r\n",
            strpos($stream, $needle)
        );

        return response($stream)
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }
}
