<?php

namespace LinkedInResumeParser\Section;

use JsonSerializable;

/**
 * Class EducationEntry
 *
 * @package LinkedInResumeParser\Section
 */
class EducationEntry implements JsonSerializable, Arrayable
{
    /**
     * @var string
     */
    protected $level;

    /**
     * @var string
     */
    protected $courseTitle;

    /**
     * @var string
     */
    protected $institution;

    /**
     * @var string
     */
    protected $grade;

    /**
     * @var string
     */
    protected $activitiesAndSocieties;

    /**
     * @var string
     */
    protected $start;

    /**
     * @var string
     */
    protected $end;

    /**
     * @return string | null
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param string $grade
     * @return EducationEntry
     */
    public function setGrade(string $grade = null): EducationEntry
    {
        $this->grade = $grade;
        return $this;
    }

    /**
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @param string $level
     * @return EducationEntry
     */
    public function setLevel(string $level): EducationEntry
    {
        $this->level = $level;
        return $this;
    }

    /**
     * @return string
     */
    public function getCourseTitle(): string
    {
        return $this->courseTitle;
    }

    /**
     * @param string $courseTitle
     * @return EducationEntry
     */
    public function setCourseTitle(string $courseTitle): EducationEntry
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
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @param string $start
     * @return EducationEntry
     */
    public function setStart(string $start): EducationEntry
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param string $end
     * @return EducationEntry
     */
    public function setEnd(string $end = null): EducationEntry
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return string
     */
    public function getActivitiesAndSocieties(): string
    {
        return $this->activitiesAndSocieties;
    }

    /**
     * @param string $activitiesAndSocieties
     * @return EducationEntry
     */
    public function setActivitiesAndSocieties(string $activitiesAndSocieties): EducationEntry
    {
        $this->activitiesAndSocieties = $activitiesAndSocieties;
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