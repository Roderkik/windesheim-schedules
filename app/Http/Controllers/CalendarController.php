<?php

namespace App\Http\Controllers;

use App\Helpers\Calendar;
use App\Helpers\Event;
use App\Helpers\WindesheimApi;
use Carbon\Carbon;
use Illuminate\Http\Response;

class CalendarController extends Controller
{
    private WindesheimApi $api;
    private Calendar $calendar;

    /**
     * Initializes controller properties.
     */
    public function __construct()
    {
        $this->api = new WindesheimApi();
        $this->calendar = new Calendar();
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
        $this->buildCalendar($class);

        return $this->calendarResponse();
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
        $this->buildCalendar($teacher);

        return $this->calendarResponse();
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
        $this->buildCalendar($subject);

        return $this->calendarResponse();
    }

    /**
     * Names the calendar and adds
     * events found in api.
     *
     * @param string $name
     * @return void
     */
    private function buildCalendar(string $name): void
    {
        $timezone = "Europe/Amsterdam";

        $this->calendar
            ->customNode("X-WR-TIMEZONE:$timezone")
            ->customNode("X-WR-CALNAME:$name")
            ->customNode("NAME:$name")
            ->timeZoneNode(Carbon::now()->year, Carbon::now()->year, "Europe/Amsterdam");

        foreach ($this->api->schedule() as $scheduleDatum) {
            if (empty($scheduleDatum->vaknaam) || empty($scheduleDatum->vakcode)) {
                $scheduleDatum->vaknaam = $scheduleDatum->commentaar;
                $scheduleDatum->vakcode = $scheduleDatum->commentaar;
            }

            // Transform time to UTC
            $scheduleDatum->starttijd = Carbon::createFromTimestampMs($scheduleDatum->starttijd);
            $scheduleDatum->eindtijd = Carbon::createFromTimestampMs($scheduleDatum->eindtijd);

            $event = new Event();
            $event->title($scheduleDatum->commentaar)
                ->location($scheduleDatum->lokaal)
                ->start($scheduleDatum->starttijd, $timezone)
                ->end($scheduleDatum->eindtijd, $timezone)
                ->stamp($timezone);

            $this->calendar->event($event);
        }
    }

    /**
     * Creates the standard response
     * used by this controller.
     *
     * @return Response
     */
    private function calendarResponse(): Response
    {
        return response($this->calendar->export())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }
}
