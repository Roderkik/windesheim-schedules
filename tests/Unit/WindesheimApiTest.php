<?php

namespace Unit;

use App\Helpers\WindesheimApi;
use TestCase;

class WindesheimApiTest extends TestCase
{

    public function testClasses()
    {
        $api = new WindesheimApi();

        $this->assertNotEmpty($api->classes());
    }

    public function testSchedule()
    {
        $api = new WindesheimApi();
        $api->setClass("ICTSE1e");

        $this->assertNotEmpty($api->schedule(), "Failed class schedule test.");

        $api = new WindesheimApi();
        $api->setTeacher("BNH09");

        $this->assertNotEmpty($api->schedule(), "Failed teacher schedule test.");

        $api = new WindesheimApi();
        $api->setSubject("ICT.KBS.V19");

        $this->assertNotEmpty($api->schedule(), "Failed subject schedule test.");
    }

}
