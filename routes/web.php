<?php

/** @var Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// ical schedule generation routes:
$router->group(
    ["prefix" => "schedule", "as" => "schedule."],
    function () use ($router) {
        $router->get('class/{class}', "CalendarController@class");
        $router->get('teacher/{teacher}', "CalendarController@teacher");
        $router->get('subject/{subject}', "CalendarController@subject");
    }
);

