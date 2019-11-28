<?php

namespace App\Http\Controllers;

use App\Helpers\WindesheimApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\IcalendarGenerator\Components\Calendar;

class CalendarController extends Controller
{
    private WindesheimApi $api;
    private Calendar $calendar;

    public function __construct()
    {
        $this->api = new WindesheimApi();
        $this->calendar = new Calendar();
    }

    /**
     * Generate ical file for the schedule of a class
     *
     * @param Request $request
     * @param string $class
     * @return Response
     */
    public function class(string $class): Response
    {
        $this->api->setClass($class);

        dd($this->api->schedule());

        //TODO: Build schedule array to ical logic.

        return response("")
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }

    /**
     * Generate ical file for the schedule of a teacher
     *
     * @param Request $request
     * @param string $teacher
     * @return Response
     */
    public function teacher(string $teacher): Response
    {
        $this->api->setTeacher($teacher);

        dd($this->api->schedule());

        //TODO: Build schedule array to ical logic.

        return response("")
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }


    /**
     * Generate ical file for the schedule of a subject
     *
     * @param Request $request
     * @param string $subject
     * @return Response
     */
    public function subject(string $subject): Response
    {
        $this->api->setTeacher($subject);

        dd($this->api->schedule());

        //TODO: Build schedule array to ical logic.

        return response("")
            ->header('Content-Type', 'text/calendar')
            ->header('charset', 'utf-8');
    }
}
