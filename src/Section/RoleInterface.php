<?php

namespace LinkedInResumeParser\Section;

use DateTimeInterface;

/**
 * Interface RoleInterface
 */
interface RoleInterface
{
    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return RoleInterface
     */
    public function setTitle(string $title): RoleInterface;

    /**
     * @return string
     */
    public function getOrganisation(): string;

    /**
     * @param string $organisation
     * @return RoleInterface
     */
    public function setOrganisation(string $organisation): RoleInterface;

    /**
     * @return DateTimeInterface
     */
    public function getStart(): DateTimeInterface;

    /**
     * @param DateTimeInterface $start
     * @return RoleInterface
     */
    public function setStart(DateTimeInterface $start): RoleInterface;

    /**
     * @return DateTimeInterface | null
     */
    public function getEnd();

    /**
     * @param DateTimeInterface | null $end
     * @return RoleInterface
     */
    public function setEnd(DateTimeInterface $end = null): RoleInterface;

    /**
     * @return string
     */
    public function getSummary(): string;

    /**
     * @param string $summary
     * @return RoleInterface
     */
    public function setSummary(string $summary): RoleInterface;
}
