<?php

namespace LinkedInResumeParser\Section;

use DateTimeInterface;
use JsonSerializable;

/**
 * Class VolunteerExperienceEntry
 *
 * @package LinkedInResumeParser\Section
 */
class VolunteerExperienceEntry implements RoleInterface, JsonSerializable
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $organisation;

    /**
     * @var DateTimeInterface
     */
    protected $start;

    /**
     * @var DateTimeInterface | null
     */
    protected $end;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return RoleInterface
     */
    public function setTitle(string $title): RoleInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganisation(): string
    {
        return $this->organisation;
    }

    /**
     * @param string $organisation
     * @return RoleInterface
     */
    public function setOrganisation(string $organisation): RoleInterface
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getStart(): DateTimeInterface
    {
        return $this->start;
    }

    /**
     * @param DateTimeInterface $start
     * @return RoleInterface
     */
    public function setStart(DateTimeInterface $start): RoleInterface
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
     * @return RoleInterface
     */
    public function setEnd(DateTimeInterface $end = null): RoleInterface
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return RoleInterface
     */
    public function setSummary(string $summary): RoleInterface
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'jobTitle'     => $this->title,
            'organisation' => $this->organisation,
            'summary'      => $this->summary,
            'start'        => $this->start ? $this->start->format('Y-m-d H:i:s') : null,
            'end'          => $this->end ? $this->end->format('Y-m-d H:i:s') : null,
        ];
    }
}