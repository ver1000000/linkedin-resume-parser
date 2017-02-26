<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use DateTimeInterface;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

class HonorAward implements JsonSerializable, Arrayable, ArrayAccess
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
    protected $institution;

    /**
     * @var DateTimeInterface
     */
    protected $date;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @param string $institution
     * @return HonorAward
     */
    public function setInstitution(string $institution): HonorAward
    {
        $this->institution = $institution;
        return $this;
    }

    /**
     * @param string $summary
     * @return HonorAward
     */
    public function setSummary(string $summary): HonorAward
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @param string $summaryPart
     * @return HonorAward
     */
    public function appendSummary(string $summaryPart): HonorAward
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
     * @return HonorAward
     */
    public function setTitle(string $title): HonorAward
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param DateTimeInterface $date
     * @return HonorAward
     */
    public function setDate(DateTimeInterface $date): HonorAward
    {
        $this->date = $date;
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
            'title'       => $this->title,
            'institution' => $this->institution,
            'date'        => $this->date,
            'summary'     => $this->summary,
        ];
    }
}