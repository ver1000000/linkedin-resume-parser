<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class Course
 *
 * @package LinkedInResumeParser\Section
 */
class Course implements JsonSerializable, Arrayable, ArrayAccess
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Course
     */
    public function setName(string $name): Course
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $namePart
     * @return Course
     */
    public function appendName(string $namePart): Course
    {
        if ($this->name) {
            $this->name .= ' ' . $namePart;
        } else {
            $this->name = $namePart;
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
            'name' => $this->name,
        ];
    }
}