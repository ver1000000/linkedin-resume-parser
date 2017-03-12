<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class TestScore
 *
 * @package LinkedInResumeParser\Section
 */
class TestScore implements JsonSerializable, Arrayable, ArrayAccess
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
    protected $score;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return TestScore
     */
    public function setName(string $name): TestScore
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getScore(): string
    {
        return $this->score;
    }

    /**
     * @param string $score
     * @return TestScore
     */
    public function setScore(string $score): TestScore
    {
        $this->score = $score;
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
            'name'  => $this->name,
            'score' => $this->score,
        ];
    }

}