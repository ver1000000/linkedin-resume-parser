<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class Recommendation
 *
 * @package LinkedInResumeParser\Section
 */
class Recommendation implements JsonSerializable, Arrayable, ArrayAccess
{
    /**
     * Array Access Trait
     */
    use ArrayAccessible;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $position;

    /**
     * @var string
     */
    protected $relation;

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return Recommendation
     */
    public function setSummary(string $summary): Recommendation
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param string $summaryPart
     * @return Recommendation
     */
    public function appendSummary(string $summaryPart): Recommendation
    {
        if ($this->summary) {
            $this->summary .= ' ' . trim($summaryPart);
        } else {
            $this->summary = trim($summaryPart);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Recommendation
     */
    public function setName(string $name): Recommendation
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getRelation(): string
    {
        return $this->relation;
    }

    /**
     * @param string $relation
     * @return Recommendation
     */
    public function setRelation(string $relation): Recommendation
    {
        $this->relation = $relation;
        return $this;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @param string $position
     * @return Recommendation
     */
    public function setPosition(string $position): Recommendation
    {
        $this->position = $position;
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
            'summary'  => $this->summary,
            'name'     => $this->name,
            'position' => $this->position,
            'relation' => $this->relation,
        ];
    }
}