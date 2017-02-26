<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use DateTimeInterface;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class Organization
 *
 * @package LinkedInResumeParser\Section
 */
class Organization implements JsonSerializable, Arrayable, ArrayAccess
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
     * @var string
     */
    protected $title;

    /**
     * @var DateTimeInterface | null
     */
    protected $start;

    /**
     * @var DateTimeInterface | null
     */
    protected $end;

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
     * @return Organization
     */
    public function setName(string $name): Organization
    {
        $this->name = $name;
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
     * @return Organization
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
     * @return Organization
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @param string $summaryPart
     * @return Organization
     */
    public function appendSummary(string $summaryPart): Organization
    {
        if ($this->summary) {
            $this->summary .= ' ' . trim($summaryPart);
        } else {
            $this->summary = trim($summaryPart);
        }

        return $this;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
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
     * @param string $summary
     * @return Organization
     */
    public function setSummary(string $summary): Organization
    {
        $this->summary = $summary;
        return $this;
    }

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
            'title'   => $this->title,
            'start'   => $this->start,
            'end'     => $this->end,
            'summary' => $this->summary,
        ];
    }
}