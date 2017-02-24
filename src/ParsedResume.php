<?php

namespace LinkedInResumeParser;

use ArrayAccess;
use JsonSerializable;
use LinkedInResumeParser\Section\Arrayable;
use LinkedInResumeParser\Section\Certification;
use LinkedInResumeParser\Section\EducationEntry;
use LinkedInResumeParser\Section\Language;
use LinkedInResumeParser\Section\Role;
use LinkedInResumeParser\Section\VolunteerExperienceEntry;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class ParsedResume
 *
 * @package LinkedInResumeParser
 */
class ParsedResume implements JsonSerializable, Arrayable, ArrayAccess
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
    protected $emailAddress;

    /**
     * @var string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $interests;

    /**
     * @var Role
     */
    protected $currentRole;

    /**
     * @var Role[]
     */
    protected $previousRoles = [];

    /**
     * @var string[]
     */
    protected $skills = [];

    /**
     * @var EducationEntry[]
     */
    protected $educationEntries = [];

    /**
     * @var Certification[]
     */
    protected $certifications = [];

    /**
     * @var VolunteerExperienceEntry[]
     */
    protected $volunteerExperienceEntries = [];

    /**
     * @var Language[]
     */
    protected $languages = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ParsedResume
     */
    public function setName(string $name): ParsedResume
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     * @return ParsedResume
     */
    public function setEmailAddress(string $emailAddress): ParsedResume
    {
        $this->emailAddress = $emailAddress;
        return $this;
    }

    /**
     * @return Role
     */
    public function getCurrentRole(): Role
    {
        return $this->currentRole;
    }

    /**
     * @param Role $currentRole
     * @return ParsedResume
     */
    public function setCurrentRole(Role $currentRole): ParsedResume
    {
        $this->currentRole = $currentRole;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasCurrentRole(): bool
    {
        return ! $this->currentRole === null;
    }

    /**
     * @return Role[]
     */
    public function getPreviousRoles(): array
    {
        return $this->previousRoles;
    }

    /**
     * @param Role[] $previousRoles
     * @return ParsedResume
     */
    public function setPreviousRoles(array $previousRoles): ParsedResume
    {
        $this->previousRoles = $previousRoles;
        return $this;
    }

    /**
     * @return \string[]
     */
    public function getSkills(): array
    {
        return $this->skills;
    }

    /**
     * @param \string[] $skills
     * @return ParsedResume
     */
    public function setSkills(array $skills): ParsedResume
    {
        $this->skills = $skills;
        return $this;
    }

    /**
     * @param string $skill
     * @return ParsedResume
     */
    public function addSkill(string $skill): ParsedResume
    {
        $this->skills[] = $skill;
        return $this;
    }

    /**
     * @return EducationEntry[]
     */
    public function getEducationEntries(): array
    {
        return $this->educationEntries;
    }

    /**
     * @param EducationEntry[] $educationEntries
     * @return ParsedResume
     */
    public function setEducationEntries(array $educationEntries): ParsedResume
    {
        $this->educationEntries = $educationEntries;
        return $this;
    }

    /**
     * @return Certification[]
     */
    public function getCertifications(): array
    {
        return $this->certifications;
    }

    /**
     * @param Certification[] $certifications
     * @return ParsedResume
     */
    public function setCertifications(array $certifications): ParsedResume
    {
        $this->certifications = $certifications;
        return $this;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     * @return ParsedResume
     */
    public function setSummary(string $summary): ParsedResume
    {
        $this->summary = $summary;
        return $this;
    }

    /**
     * @return VolunteerExperienceEntry[]
     */
    public function getVolunteerExperienceEntries(): array
    {
        return $this->volunteerExperienceEntries;
    }

    /**
     * @param VolunteerExperienceEntry[] $volunteerExperienceEntries
     * @return ParsedResume
     */
    public function setVolunteerExperienceEntries(array $volunteerExperienceEntries): ParsedResume
    {
        $this->volunteerExperienceEntries = $volunteerExperienceEntries;
        return $this;
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @param Language[] $languages
     * @return ParsedResume
     */
    public function setLanguages(array $languages): ParsedResume
    {
        $this->languages = $languages;
        return $this;
    }

    /**
     * @return string
     */
    public function getInterests(): string
    {
        return $this->interests;
    }

    /**
     * @param string $interests
     * @return ParsedResume
     */
    public function setInterests(string $interests): ParsedResume
    {
        $this->interests = $interests;
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
            'name'                       => $this->name,
            'emailAddress'               => $this->emailAddress,
            'summary'                    => $this->summary,
            'interests'                  => $this->interests,
            'skills'                     => $this->skills,
            'currentRole'                => $this->currentRole ? $this->currentRole->toArray() : null,
            'previousRoles'              => $this->itemsToArray($this->previousRoles),
            'educationEntries'           => $this->itemsToArray($this->educationEntries),
            'certifications'             => $this->itemsToArray($this->certifications),
            'volunteerExperienceEntries' => $this->itemsToArray($this->volunteerExperienceEntries),
            'languages'                  => $this->itemsToArray($this->languages),
        ];
    }

    /**
     * @param array $items
     * @return array
     */
    protected function itemsToArray(array $items)
    {
        return array_map(function (Arrayable $item) {
            return $item->toArray();
        }, $items);
    }
}