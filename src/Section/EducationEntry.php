<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use DateTimeInterface;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class EducationEntry
 *
 * @package LinkedInResumeParser\Section
 */
class EducationEntry implements JsonSerializable, Arrayable, ArrayAccess
{
    /**
     * Array Access Trait
     */
    use ArrayAccessible;

    /**
     * @var string | null
     */
    protected $level;

    /**
     * @var string | null
     */
    protected $courseTitle;

    /**
     * @var string
     */
    protected $institution;

    /**
     * @var string | null
     */
    protected $grade;

    /**
     * @var string | null
     */
    protected $activitiesAndSocieties;

    /**
     * @var DateTimeInterface | null
     */
    protected $start;

    /**
     * @var DateTimeInterface | null
     */
    protected $end;

    /**
     * @return null|string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param null|string $level
     * @return EducationEntry
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getCourseTitle()
    {
        return $this->courseTitle;
    }

    /**
     * @param null|string $courseTitle
     * @return EducationEntry
     */
    public function setCourseTitle($courseTitle)
    {
        $this->courseTitle = $courseTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstitution(): string
    {
        return $this->institution;
    }

    /**
     * @param string $institution
     * @return EducationEntry
     */
    public function setInstitution(string $institution): EducationEntry
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param null|string $grade
     * @return EducationEntry
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getActivitiesAndSocieties()
    {
        return $this->activitiesAndSocieties;
    }

    /**
     * @param null|string $activitiesAndSocieties
     * @return EducationEntry
     */
    public function setActivitiesAndSocieties($activitiesAndSocieties)
    {
        $this->activitiesAndSocieties = $activitiesAndSocieties;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param DateTimeInterface|null $start
     * @return EducationEntry
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param DateTimeInterface|null $end
     * @return EducationEntry
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'level'                    => $this->level,
            'course_title'             => $this->courseTitle,
            'institution'              => $this->institution,
            'grade'                    => $this->grade,
            'activities_and_societies' => $this->activitiesAndSocieties,
            'start'                    => $this->start,
            'end'                      => $this->end,
        ];
    }
}