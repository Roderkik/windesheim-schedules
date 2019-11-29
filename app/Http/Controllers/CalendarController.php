<?php

namespace App\Http\Controllers;

use App\Helpers\WindesheimApi;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;

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
     * @param string $calendarName
     * @return void
     */
    private function buildCalendar(string $calendarName): void
    {
        $this->calendar->name($calendarName);

        foreach ($this->api->schedule() as $scheduleDatum) {
            if (empty($scheduleDatum->vaknaam) || empty($scheduleDatum->vakcode)) {
                $scheduleDatum->vaknaam = $scheduleDatum->commentaar;
                $scheduleDatum->vakcode = $scheduleDatum->commentaar;
            }

            $event = new Event($scheduleDatum->vaknaam);
            $event->description($scheduleDatum->commentaar);
            $event->address($scheduleDatum->lokaal);
            $event->startsAt(Carbon::createFromTimestampMs($scheduleDatum->starttijd));
            $event->endsAt(Carbon::createFromTimestampMs($scheduleDatum->eindtijd));

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
        return response($this->calendar->get())
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }
}
