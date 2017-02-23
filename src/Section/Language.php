<?php

namespace LinkedInResumeParser\Section;

use JsonSerializable;

/**
 * Class Language
 *
 * @package LinkedInResumeParser\Section
 */
class Language implements JsonSerializable, Arrayable
{
    /**
     * @var string
     */
    protected $language;

    /**
     * @var string
     */
    protected $level;

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * @param string $language
     * @return Language
     */
    public function setLanguage(string $language): Language
    {
        $this->language = $language;
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
     * @return Language
     */
    public function setLevel(string $level): Language
    {
        $this->level = $level;
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
            'language' => $this->language,
            'level'    => $this->level,
        ];
    }
}