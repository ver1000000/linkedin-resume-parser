<?php

namespace LinkedInResumeParser;

use DateTime;
use LinkedInResumeParser\Exception\FileNotFoundException;
use LinkedInResumeParser\Exception\FileNotReadableException;
use LinkedInResumeParser\Exception\ParseException;
use LinkedInResumeParser\Section\Certification;
use LinkedInResumeParser\Section\EducationEntry;
use LinkedInResumeParser\Section\Language;
use LinkedInResumeParser\Section\Role;
use LinkedInResumeParser\Section\RoleInterface;
use LinkedInResumeParser\Section\VolunteerExperienceEntry;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Parser as PdfParser;

/**
 * Class Parser
 *
 * @package Persata\LinkedInResumeParser
 */
class Parser
{
    /**
     * Constants that designate the various sections of the resume
     */
    const SUMMARY_TITLE = 'Summary';
    const EXPERIENCE_TITLE = 'Experience';
    const SKILLS_AND_EXPERTISE_TITLE = 'Skills & Expertise';
    const EDUCATION_TITLE = 'Education';
    const CERTIFICATIONS_TITLE = 'Certifications';
    const VOLUNTEER_EXPERIENCE_TITLE = 'Volunteer Experience';
    const LANGUAGES_TITLE = 'Languages';
    const INTERESTS_TITLE = 'Interests';
    const ORGANIZATIONS_TITLE = 'Organizations';
    const COURSES_TITLE = 'Courses';
    const PROJECTS_TITLE = 'Projects';
    const HONORS_AND_AWARDS_TITLE = 'Honors and Awards';

    /**
     * Section titles for each part of the resume
     *
     * @var string[]
     */
    protected $sectionTitles = [
        self::SUMMARY_TITLE,
        self::EXPERIENCE_TITLE,
        self::SKILLS_AND_EXPERTISE_TITLE,
        self::EDUCATION_TITLE,
        self::CERTIFICATIONS_TITLE,
        self::VOLUNTEER_EXPERIENCE_TITLE,
        self::LANGUAGES_TITLE,
        self::INTERESTS_TITLE,
        self::ORGANIZATIONS_TITLE,
        self::COURSES_TITLE,
        self::PROJECTS_TITLE,
        self::HONORS_AND_AWARDS_TITLE,
    ];

    /**
     * @param string $filePath
     * @return ParsedResume
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws ParseException
     * @throws \Exception
     */
    public function parse(string $filePath): ParsedResume
    {
        if ( ! file_exists($filePath)) {
            throw new FileNotFoundException("The file at $filePath does not exist.");
        }

        if ( ! is_readable($filePath)) {
            throw new FileNotReadableException("The file at $filePath is not readable.");
        }

        $parsedPdfInstance = $this->getParsedPdfInstance($filePath);

        $textLines = $this->getAllTextLines($parsedPdfInstance);
        $textLines = $this->filterText($textLines);

        $parsedResumeInstance = new ParsedResume();

        $name = $textLines[0];
        $parsedResumeInstance->setName($name);

        // @TODO - Find this programmatically
        // $emailAddress = $textLines[2];
        // $parsedResumeInstance->setEmailAddress($emailAddress);

        $textLines = $this->removeLastSection($textLines, $name);

        $skills = $this->getSkills($textLines);
        $parsedResumeInstance->setSkills($skills);

        if ($summary = $this->getSummary($textLines)) {
            $parsedResumeInstance->setSummary($summary);
        }

        $roles = $this->getRoles($textLines);

        // Check if their latest role has ended, i.e. it is not their current role
        $latestRole = reset($roles);
        if ($latestRole->getEnd() === null) {
            // Remove it from our list since we're setting previous roles
            array_shift($roles);

            // Set it as the current role
            $parsedResumeInstance->setCurrentRole($latestRole);
        }

        $parsedResumeInstance->setPreviousRoles($roles);

        $educationEntries = $this->getEducationEntries($textLines);
        $parsedResumeInstance->setEducationEntries($educationEntries);

        $certifications = $this->getCertifications($textLines);
        $parsedResumeInstance->setCertifications($certifications);

        $volunteerExperienceEntries = $this->getVolunteerExperienceEntries($textLines);
        $parsedResumeInstance->setVolunteerExperienceEntries($volunteerExperienceEntries);

        $languages = $this->getLanguages($textLines);
        $parsedResumeInstance->setLanguages($languages);

        $interests = $this->getInterests($textLines);
        $parsedResumeInstance->setInterests($interests);

        return $parsedResumeInstance;
    }

    /**
     * @param string $filePath
     * @return Document
     */
    protected function getParsedPdfInstance(string $filePath): Document
    {
        $pdfParser = new PdfParser();
        return $pdfParser->parseFile($filePath);
    }

    /**
     * @param Document $parsedPdfInstance
     * @return \string[]
     * @throws \Exception
     */
    protected function getAllTextLines(Document $parsedPdfInstance): array
    {
        $textLines = [];

        foreach ($parsedPdfInstance->getPages() as $page) {
            $textLines = array_merge($textLines, $page->getTextArray());
        }

        return $textLines;
    }

    /**
     * @param array $textLines
     * @return string[]
     */
    protected function filterText(array $textLines): array
    {
        $filteredTextLines = [];

        for ($i = 0; $i < count($textLines); $i++) {
            if ($this->isPageDesignation($i, $textLines)) {
                $i++;
                continue;
            } else {
                $filteredTextLines[] = $textLines[$i];
            }
        }

        return $filteredTextLines;
    }

    /**
     * Check if the given index is indicative of being a Page designation
     * e.g. current index will be "Page" and then the immediate index will be the number
     *
     * @param int   $index
     * @param array $textLines
     * @return bool
     */
    protected function isPageDesignation(int $index, array $textLines): bool
    {
        return $textLines[$index] === 'Page' && is_numeric($textLines[$index + 1]);
    }

    /**
     * @param array  $textLines
     * @param string $name
     * @return string[]
     */
    protected function removeLastSection(array $textLines, string $name): array
    {
        $lastNameOccurrence = array_search($name, array_reverse($textLines));
        array_splice($textLines, count($textLines) - $lastNameOccurrence - 1);
        return $textLines;
    }

    /**
     * @param array $textLines
     * @return array
     */
    protected function getSkills(array $textLines): array
    {
        return $this->findSectionLines(self::SKILLS_AND_EXPERTISE_TITLE, $textLines);
    }

    /**
     * @param string $sectionTitle
     * @param array  $textLines
     * @return array
     */
    protected function findSectionLines(string $sectionTitle, array $textLines): array
    {
        $startIndex = array_search($sectionTitle, $textLines);

        if ($startIndex === false) {
            return [];
        }

        $endIndex = $this->findSectionIndexEnd($startIndex, $textLines);

        return array_slice($textLines, $startIndex + 1, $endIndex - $startIndex - 1);
    }

    /**
     * @param int   $startIndex
     * @param array $textLines
     * @return int
     */
    protected function findSectionIndexEnd(int $startIndex, array $textLines): int
    {
        for ($i = $startIndex + 1; $i < count($textLines); $i++) {
            if (in_array($textLines[$i], $this->sectionTitles)) {
                return $i;
            }
        }

        return count($textLines);
    }

    /**
     * @param array $textLines
     * @return string | null
     */
    protected function getSummary(array $textLines)
    {
        $startIndex = array_search(self::SUMMARY_TITLE, $textLines);

        if ($startIndex === false) {
            return null;
        }

        $endIndex = $this->findSectionIndexEnd($startIndex, $textLines);

        return implode('',
            array_slice($textLines, $startIndex + 1, $endIndex - $startIndex - 1)
        );
    }

    /**
     * @param array $textLines
     * @return Role[]
     * @throws ParseException
     */
    protected function getRoles(array $textLines): array
    {
        $roleLines = $this->findSectionLines(self::EXPERIENCE_TITLE, $textLines);
        return $this->buildRoleTypes(Role::class, $roleLines);
    }

    /**
     * @param string $classType
     * @param array  $roleLines
     * @return array
     * @throws ParseException
     */
    protected function buildRoleTypes(string $classType, array $roleLines): array
    {
        $roleTypes = [];

        for ($i = 0; $i < count($roleLines); $i++) {

            $roleLine = $roleLines[$i];

            /** @var RoleInterface $roleType */

            if ($this->isRoleDescriptionLine($roleLine)) {
                $roleDescriptionLine = $roleLine;

                // Marks the start of a new role, so add it to the list of roles
                if (isset($roleType)) {
                    $roleTypes[] = $roleType;
                }

                // There may be more lines that follow that should be appended to the role description
                // @TODO - Revisit this logic to handle multi-line descriptions
//                for ($roleDescriptionIndex = $i + 1; $roleDescriptionIndex < count($roleLines); $roleDescriptionIndex++) {
//                    if (preg_match('/\s{2}-\s{2}/', $roleLines[$roleDescriptionIndex])) {
//                        break;
//                    } else {
//                        $roleDescriptionLine .= ' ' . $roleLines[$roleDescriptionIndex];
//                        $i++;
//                    }
//                }

                // Begin parsing the new role
                list($title, $organisation) = $this->parseRoleParts($roleDescriptionLine);

                /** @var RoleInterface $roleType */
                $roleType = (new $classType());

                $roleType
                    ->setTitle($title)
                    ->setOrganisation($organisation);

            } elseif (preg_match('/\s{2}-\s{2}/', $roleLine)) { // Date range
                list($startDate, $endDate) = $this->parseRoleDates($roleLine);
                $roleType
                    ->setStart($startDate)
                    ->setEnd($endDate);
            } elseif ( ! preg_match('/^\(.*\)$/', $roleLine)) { // Not time description, so make it part of the summary
                $roleType->appendSummary($roleLine);
            }
        }

        if (isset($roleType)) {
            $roleTypes[] = $roleType;
        }

        return $roleTypes;
    }

    /**
     * @param string $textLine
     * @return bool
     */
    protected function isRoleDescriptionLine(string $textLine): bool
    {
        return preg_match('/\s{2}at\s{3}/', $textLine);
    }

    /**
     * @param string $roleLine
     * @return array
     * @throws ParseException
     */
    protected function parseRoleParts(string $roleLine): array
    {
        $roleParts = $this->splitAndTrim('  at  ', $roleLine);

        if (count($roleParts) === 2) {
            return $roleParts;
        } else {
            throw new ParseException("There was an error parsing the job title and organisation from the role line '${roleLine}'");
        }
    }

    /**
     * @param string $delimiter
     * @param string $string
     * @return array
     */
    protected function splitAndTrim(string $delimiter, string $string): array
    {
        return array_map(
            'trim',
            explode($delimiter, $string)
        );
    }

    /**
     * @param string $datesLine
     * @return array
     * @throws ParseException
     */
    protected function parseRoleDates(string $datesLine): array
    {
        $dateParts = $this->splitAndTrim('-', $datesLine);

        if (count($dateParts) === 2) {

            $startDateTime = $this->parseStringToDateTime($dateParts[0]);

            if ($dateParts[1] === 'Present') {
                $endDateTime = null;
            } else {
                $endDateTime = $this->parseStringToDateTime($dateParts[1]);
            }

            return [
                $startDateTime,
                $endDateTime,
            ];
        } else {
            throw new ParseException("There was an error parsing the role dates from the line '${datesLine}'");
        }
    }

    /**
     * @param string $string
     * @return DateTime
     * @throws ParseException
     */
    protected function parseStringToDateTime(string $string): DateTime
    {
        if (preg_match('/\w{1,}\s\d{4}/', $string)) {
            return DateTime::createFromFormat('H:i:s d F Y', '00:00:00 01 ' . $string);
        } elseif (preg_match('/\d{4}/', $string)) {
            return DateTime::createFromFormat('H:i:s d m Y', '00:00:00 01 01 ' . $string);
        } else {
            throw new ParseException("Unable to parse a valid date time from '${string}'");
        }
    }

    /**
     * @param array $textLines
     * @return array
     * @throws ParseException
     */
    protected function getCertifications(array $textLines): array
    {
        $certificationLines = $this->findSectionLines(self::CERTIFICATIONS_TITLE, $textLines);

        $certifications = [];

        for ($i = 0; $i < count($certificationLines); $i += 2) {
            $certification = (new Certification())
                ->setTitle($certificationLines[$i]);

            $certification = $this->addCertificationParts($certification, $certificationLines[$i + 1]);

            $certifications[] = $certification;
        }

        return $certifications;
    }

    /**
     * @param Certification $certification
     * @param string        $textLine
     * @return Certification
     * @throws ParseException
     */
    protected function addCertificationParts(Certification $certification, string $textLine): Certification
    {
        if (preg_match('/(.*?)\s{3}License\s(.*?)\s{4}(.*?\s\d{4})\sto\s(.*\d{4}$)/', $textLine, $matches)) {
            $certification
                ->setAuthority($matches[1])
                ->setLicense($matches[2])
                ->setObtainedOn($this->parseStringToDateTime($matches[3]))
                ->setValidUntil($this->parseStringToDateTime($matches[4]));
        } elseif (preg_match('/(.*?)\s{3}License\s(.*?)\s{4}(.*?\s\d{4}$)/', $textLine, $matches)) {
            $certification
                ->setAuthority($matches[1])
                ->setLicense($matches[2])
                ->setObtainedOn($this->parseStringToDateTime($matches[3]));
        } elseif (preg_match('/(.*?)\s{3}\s{4}(.*?\s\d{4}$)/', $textLine, $matches)) {
            $certification
                ->setAuthority($matches[1])
                ->setObtainedOn($this->parseStringToDateTime($matches[2]));
        } elseif (preg_match('/(.*?)\s{3}License\s(.*?)\s{3}$/', $textLine, $matches)) {
            $certification
                ->setAuthority($matches[1])
                ->setLicense($matches[2]);
        } elseif (preg_match('/(.*?)\s{6,}$/', $textLine, $matches)) {
            $certification->setAuthority($matches[1]);
        } else {
            throw new ParseException("Unable to parse certification parts from the string ${textLine}");
        }

        return $certification;
    }

    /**
     * @param array $textLines
     * @return array
     * @throws ParseException
     */
    protected function getVolunteerExperienceEntries(array $textLines): array
    {
        $volunteerExperienceLines = $this->findSectionLines(self::VOLUNTEER_EXPERIENCE_TITLE, $textLines);
        return $this->buildRoleTypes(VolunteerExperienceEntry::class, $volunteerExperienceLines);
    }

    /**
     * @param array $textLines
     * @return array
     */
    protected function getLanguages(array $textLines): array
    {
        $languageLines = $this->findSectionLines(self::LANGUAGES_TITLE, $textLines);

        $languages = [];

        for ($i = 0; $i < count($languageLines); $i++) {
            if (isset($languageLines[$i + 1]) && preg_match('/^\((.*)\sproficiency\)$/', $languageLines[$i + 1], $languageLevelMatches)) {
                $languages[] = (new Language())
                    ->setLanguage($languageLines[$i])
                    ->setLevel($languageLevelMatches[1]);
                $i++;
            } else {
                $languages[] = (new Language())
                    ->setLanguage($languageLines[$i]);
            }
        }

        return $languages;
    }

    /**
     * @param array $textLines
     * @return array
     * @throws ParseException
     */
    protected function getEducationEntries(array $textLines): array
    {
        $educationLines = $this->findSectionLines(self::EDUCATION_TITLE, $textLines);

        $educationEntries = [];

        for ($i = 0; $i < count($educationLines); $i++) {

            $educationLine = $educationLines[$i];

            /** @var EducationEntry $educationEntry */

            if (preg_match('/(.*?)\,\s(.*?)\,\s(\d{4})\s-\s(\d{4})$/', $educationLine, $matches)) { // "Bachelor of Arts, Theatre Management, 2006 - 2010"
                $educationEntry
                    ->setDegreeLevel($matches[1])
                    ->setDegree($matches[2])
                    ->setStart($matches[3])
                    ->setEnd($matches[4]);
            } elseif (preg_match('/(.*?)\,\s(.*?)\,\s(\d{4})$/', $educationLine, $matches)) { // "Bachelor’s Degree, Biomedical Engineering, 2014"
                $educationEntry
                    ->setDegreeLevel($matches[1])
                    ->setDegree($matches[2])
                    ->setEnd($matches[3]);
            } elseif (preg_match('/(.*?),\s(\d{4})/', $educationLine, $matches)) { // "High School, 2009"
                $educationEntry
                    ->setDegreeLevel($matches[1])
                    ->setEnd($matches[2]);
            } elseif (preg_match('/(\d{4})\s-\s(\d{4})/', $educationLine, $matches)) { // "2002 - 2006"
                $educationEntry
                    ->setStart($matches[1])
                    ->setEnd($matches[2]);
            } elseif (trim($educationLine) === 'Activities and Societies:') { // "Activities and Societies: "
                // At least one line belongs to "Activities and Societies"
                $activitiesAndSocieties = $educationLines[$i + 1];
                // Modify the index to skip any lines we process here
                $i++;
                // And there may be more lines that start with a space that should be appended to the activities
                for ($activitiesAndSocietiesIndex = $i + 1; $activitiesAndSocietiesIndex < count($educationLines); $activitiesAndSocietiesIndex++) {
                    if (preg_match('/^\s(.*)$/', $educationLines[$activitiesAndSocietiesIndex])) {
                        $activitiesAndSocieties .= $educationLines[$activitiesAndSocietiesIndex];
                        $i++;
                    } else {
                        break;
                    }
                }
                $educationEntry->setActivitiesAndSocieties($activitiesAndSocieties);
            } elseif (trim($educationLine) === 'Grade:') { // "Grade: "
                $educationEntry->setGrade($educationLines[$i + 1]);
                $i++;
            } else {
                // If we can't identify this line, it likely marks the start of a new education entry, so add it to the list of entries and start a new one.
                if (isset($educationEntry)) {
                    $educationEntries[] = $educationEntry;
                }

                $educationEntry = (new EducationEntry())->setInstitution($educationLine);
            }
        }

        if (isset($educationEntry)) {
            $educationEntries[] = $educationEntry;
        }

        return $educationEntries;
    }

    /**
     * @param string $educationLine
     * @return array
     */
    protected function parseEducationParts(string $educationLine): array
    {
        $parts = $this->splitAndTrim(',', $educationLine);

        $partsCount = count($parts);

        $degreeLevel = $parts[0];
        $degree = implode(', ', array_slice($parts, 1, $partsCount - 2));
        $dateParts = $this->splitAndTrim('-', $parts[$partsCount - 1]);

        return [
            $degreeLevel,
            $degree,
            $dateParts[0],
            $dateParts[1],
        ];
    }

    /**
     * @param array $textLines
     * @return string
     */
    protected function getInterests(array $textLines): string
    {
        $interestLines = $this->findSectionLines(self::INTERESTS_TITLE, $textLines);
        return implode('', $interestLines);
    }
}