<?php

namespace LinkedInResumeParser\Section;

use JsonSerializable;

/**
 * Class EducationEntry
 *
 * @package LinkedInResumeParser\Section
 */
class EducationEntry implements JsonSerializable
{
    /**
     * @var string
     */
    protected $degreeLevel;

    /**
     * @var string
     */
    protected $degree;

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
    public function getDegreeLevel(): string
    {
        return $this->degreeLevel;
    }

    /**
     * @param string $degreeLevel
     * @return EducationEntry
     */
    public function setDegreeLevel(string $degreeLevel): EducationEntry
    {
        $this->degreeLevel = $degreeLevel;
        return $this;
    }

    /**
     * @return string
     */
    public function getDegree(): string
    {
        return $this->degree;
    }

    /**
     * @param string $degree
     * @return EducationEntry
     */
    public function setDegree(string $degree): EducationEntry
    {
        $this->degree = $degree;
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
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'degreeLevel'            => $this->degreeLevel,
            'degree'                 => $this->degree,
            'institution'            => $this->institution,
            'grade'                  => $this->grade,
            'activitiesAndSocieties' => $this->activitiesAndSocieties,
            'start'                  => $this->start,
            'end'                    => $this->end,
        ];
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
}