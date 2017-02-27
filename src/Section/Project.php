<?php

namespace LinkedInResumeParser\Section;

use DateTimeInterface;
use ArrayAccess;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class Project
 *
 * @package LinkedInResumeParser\Section
 */
class Project implements JsonSerializable, Arrayable, ArrayAccess
{
    /**
     * Array Access Trait
     */
    use ArrayAccessible;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var DateTimeInterface | null
     */
    protected $start;

    /**
     * @var DateTimeInterface | null
     */
    protected $end;

    /**
     * @var string[]
     */
    protected $members = [];

    /**
     * @var string | null
     */
    protected $summary;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Project
     */
    public function setName(string $name): Project
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $namePart
     * @return Project
     */
    public function appendName(string $namePart): Project
    {
        if ($this->name) {
            $this->name .= ' ' . trim($namePart);
        } else {
            $this->name = trim($namePart);
        }

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
     * @return Project
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
     * @return Project
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getMembers(): array
    {
        return $this->members;
    }

    /**
     * @param \string[] $members
     * @return Project
     */
    public function setMembers(array $members): Project
    {
        $this->members = $members;
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
     * @return Project
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param string $summaryPart
     * @return Project
     */
    public function appendSummary(string $summaryPart): Project
    {
        if ($this->summary) {
            $this->summary .= ' ' . trim($summaryPart);
        } else {
            $this->summary = trim($summaryPart);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
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
            'name'    => $this->name,
            'start'   => $this->start,
            'end'     => $this->end,
            'members' => $this->members,
            'summary' => $this->summary,
        ];
    }
}