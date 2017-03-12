<?php

namespace LinkedInResumeParser;

use ArrayAccess;
use JsonSerializable;
use LinkedInResumeParser\Section\Arrayable;
use LinkedInResumeParser\Section\Certification;
use LinkedInResumeParser\Section\Course;
use LinkedInResumeParser\Section\EducationEntry;
use LinkedInResumeParser\Section\HonorAward;
use LinkedInResumeParser\Section\Language;
use LinkedInResumeParser\Section\Organization;
use LinkedInResumeParser\Section\Project;
use LinkedInResumeParser\Section\Recommendation;
use LinkedInResumeParser\Section\Role;
use LinkedInResumeParser\Section\TestScore;
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
     * @var HonorAward[]
     */
    protected $honorsAndAwards = [];

    /**
     * @var Organization[]
     */
    protected $organizations = [];

    /**
     * @var Course[]
     */
    protected $courses = [];

    /**
     * @var Project[]
     */
    protected $projects = [];

    /**
     * @var Recommendation[]
     */
    protected $recommendations = [];

    /**
     * @var TestScore[]
     */
    protected $testScores = [];

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
        return ! ($this->currentRole === null);
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
     * @return Role[]
     */
    public function getAllRoles(): array
    {
        if ($this->hasCurrentRole()) {
            return array_merge([$this->getCurrentRole()], $this->getPreviousRoles());
        } else {
            return $this->getPreviousRoles();
        }
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
     * @return HonorAward[]
     */
    public function getHonorsAndAwards(): array
    {
        return $this->honorsAndAwards;
    }

    /**
     * @param HonorAward[] $honorsAndAwards
     * @return $this
     */
    public function setHonorsAndAwards(array $honorsAndAwards)
    {
        $this->honorsAndAwards = $honorsAndAwards;
        return $this;
    }

    /**
     * @return Organization[]
     */
    public function getOrganizations(): array
    {
        return $this->organizations;
    }

    /**
     * @param Organization[] $organizations
     * @return $this
     */
    public function setOrganizations(array $organizations)
    {
        $this->organizations = $organizations;
        return $this;
    }

    /**
     * @return Course[]
     */
    public function getCourses(): array
    {
        return $this->courses;
    }

    /**
     * @param Course[] $courses
     * @return ParsedResume
     */
    public function setCourses(array $courses): ParsedResume
    {
        $this->courses = $courses;
        return $this;
    }

    /**
     * @return Project[]
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * @param Project[] $projects
     * @return ParsedResume
     */
    public function setProjects(array $projects): ParsedResume
    {
        $this->projects = $projects;
        return $this;
    }

    /**
     * @param Recommendation[] $recommendations
     * @return ParsedResume
     */
    public function setRecommendations(array $recommendations): ParsedResume
    {
        $this->recommendations = $recommendations;
        return $this;
    }

    /**
     * @return Recommendation[]
     */
    public function getRecommendations(): array
    {
        return $this->recommendations;
    }

    /**
     * @param TestScore[] $testScores
     * @return ParsedResume
     */
    public function setTestScores(array $testScores): ParsedResume
    {
        $this->testScores = $testScores;
        return $this;
    }

    /**
     * @return TestScore[]
     */
    public function getTestScores(): array
    {
        return $this->testScores;
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
            'honorsAndAwards'            => $this->itemsToArray($this->honorsAndAwards),
            'organizations'              => $this->itemsToArray($this->organizations),
            'courses'                    => $this->itemsToArray($this->courses),
            'projects'                   => $this->itemsToArray($this->projects),
            'recommendations'            => $this->itemsToArray($this->recommendations),
            'testScores'                 => $this->itemsToArray($this->testScores),
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