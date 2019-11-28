<?php

namespace App\Helpers;

use InvalidArgumentException;
use LogicException;
use Mockery\Exception;

class WindesheimApi
{
    private const BASE_URL = "http://api.windesheim.nl/api/";

    public const CLASS_URI = "Klas/";
    public const SUBJECT_URI = "Vak/";
    public const TEACHER_URI = "Docent/";
    public const LESSONS_URI = "Les/";

    private string $class;
    private string $teacher;
    private string $subject;

    /**
     * Initializes the schedule types to empty strings.
     *
     * WindesheimApi constructor.
     */
    public function __construct()
    {
        $this->class = "";
        $this->teacher = "";
        $this->subject = "";
    }

    /**
     * Gets all classes currently available.
     *
     * @return array
     * @var array $classes
     * @var string $requestUrl
     */
    public function classes(): array
    {
        $requestUrl = self::BASE_URL . self::CLASS_URI;
        $classes = json_decode(file_get_contents($requestUrl));

        if (empty($classes)) {
            throw new Exception("Empty response from $requestUrl");
        }

        return $classes;
    }

    /**
     * Generates the schedule of whichever
     * schedule type is available.
     *
     * @return array
     */
    public function schedule(): array
    {
        if ($this->class) {
            $scheduleType = self::CLASS_URI;
            $scheduleOf = $this->class;
        } elseif ($this->teacher) {
            $scheduleType = self::TEACHER_URI;
            $scheduleOf = $this->teacher;
        } elseif ($this->subject) {
            $scheduleType = self::SUBJECT_URI;
            $scheduleOf = $this->subject;
        } else {
            throw new LogicException(
                "Invalid method use for " . __METHOD__ . ": set property: class, teacher or subject first!"
            );
        }

        $requestUrl = self::BASE_URL . $scheduleType . $scheduleOf . self::LESSONS_URI;
        $classes = json_decode(file_get_contents($requestUrl));

        if (empty($classes)) {
            throw new Exception("Empty response from $requestUrl");
        }

        return $classes;
    }

    /**
     * @param string $class
     * @return WindesheimApi
     */
    public function setClass(string $class): WindesheimApi
    {
        if (empty($class)) {
            throw new InvalidArgumentException("Invalid value for class: empty");
        }

        $this->class = $this->addTrailingSlash($class);

        return $this;
    }

    /**
     * Adds a trailing slash to a string
     * if it didn't have one yet.
     *
     * @param string $string
     * @return string
     */
    private function addTrailingSlash(string $string): string
    {
        if (substr($string, -1) !== "/") {
            $string .= "/";
        }

        return $string;
    }

    /**
     * @param string $teacher
     * @return WindesheimApi
     */
    public function setTeacher(string $teacher): WindesheimApi
    {
        if (empty($teacher)) {
            throw new InvalidArgumentException("Invalid value for teacher: empty");
        }

        $this->teacher = $this->addTrailingSlash($teacher);

        return $this;
    }

    /**
     * @param string $subject
     * @return WindesheimApi
     */
    public function setSubject(string $subject): WindesheimApi
    {
        if (empty($subject)) {
            throw new InvalidArgumentException("Invalid value for subject: empty");
        }

        $this->subject = $this->addTrailingSlash($subject);

        return $this;
    }
}
