<?php

namespace LinkedInResumeParser\Section;

use ArrayAccess;
use DateTimeInterface;
use JsonSerializable;
use LinkedInResumeParser\Traits\ArrayAccessible;

/**
 * Class Certification
 *
 * @package LinkedInResumeParser\Section
 */
class Certification implements JsonSerializable, Arrayable, ArrayAccess
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
    protected $license;

    /**
     * @var string
     */
    protected $authority;

    /**
     * @var DateTimeInterface
     */
    protected $obtainedOn;

    /**
     * @var DateTimeInterface | null
     */
    protected $validUntil;

    /**
     * @return string
     */
    public function getAuthority(): string
    {
        return $this->authority;
    }

    /**
     * @param string $authority
     * @return Certification
     */
    public function setAuthority(string $authority): Certification
    {
        $this->authority = $authority;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Certification
     */
    public function setTitle(string $title): Certification
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getLicense(): string
    {
        return $this->license;
    }

    /**
     * @param string $license
     * @return Certification
     */
    public function setLicense(string $license): Certification
    {
        $this->license = $license;
        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getObtainedOn(): DateTimeInterface
    {
        return $this->obtainedOn;
    }

    /**
     * @param DateTimeInterface $obtainedOn
     * @return Certification
     */
    public function setObtainedOn(DateTimeInterface $obtainedOn): Certification
    {
        $this->obtainedOn = $obtainedOn;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * @param DateTimeInterface|null $validUntil
     * @return Certification
     */
    public function setValidUntil($validUntil)
    {
        $this->validUntil = $validUntil;
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
            'title'       => $this->title,
            'license'     => $this->license,
            'authority'   => $this->authority,
            'obtained_on' => $this->obtainedOn ?? null,
        ];
    }
}