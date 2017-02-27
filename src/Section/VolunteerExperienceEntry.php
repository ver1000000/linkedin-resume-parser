<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use DateTimeInterface;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class VolunteerExperienceEntry
 *
 * @package LinkedInResumeParser\Section
 */
class VolunteerExperienceEntry implements RoleInterface, JsonSerializable, Arrayable, ArrayAccess
{
    /**
     * Array Access Trait
     */
    use ArrayAccessible;

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
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param null|string $title
     * @return VolunteerExperienceEntry | RoleInterface
     */
    public function setTitle(string $title): RoleInterface
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param null|string $organisation
     * @return VolunteerExperienceEntry | RoleInterface
     */
    public function setOrganisation(string $organisation): RoleInterface
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param null|string $summary
     * @return VolunteerExperienceEntry | RoleInterface
     */
    public function setSummary(string $summary): RoleInterface
    {
        $this->summary = $summary;
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
     * @return VolunteerExperienceEntry | RoleInterface
     */
    public function setStart(DateTimeInterface $start = null): RoleInterface
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
     * @return VolunteerExperienceEntry | RoleInterface
     */
    public function setEnd(DateTimeInterface $end = null): RoleInterface
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
            'title'        => $this->title,
            'organisation' => $this->organisation,
            'summary'      => $this->summary,
            'start'        => $this->start ?? null,
            'end'          => $this->end ?? null,
        ];
    }
}