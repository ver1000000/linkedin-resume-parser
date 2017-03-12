<?php

namespace LinkedInResumeParser;

use DateTime;
use LinkedInResumeParser\Exception\FileNotFoundException;
use LinkedInResumeParser\Exception\FileNotReadableException;
use LinkedInResumeParser\Exception\ParseException;
use LinkedInResumeParser\Pdf\TextLine;
use LinkedInResumeParser\Section\Certification;
use LinkedInResumeParser\Section\Course;
use LinkedInResumeParser\Section\EducationEntry;
use LinkedInResumeParser\Section\Language;
use LinkedInResumeParser\Section\Organization;
use LinkedInResumeParser\Section\Project;
use LinkedInResumeParser\Section\Recommendation;
use LinkedInResumeParser\Section\Role;
use LinkedInResumeParser\Section\RoleInterface;
use LinkedInResumeParser\Section\VolunteerExperienceEntry;
use LinkedInResumeParser\Section\HonorAward;
use Smalot\PdfParser\Document;
use Smalot\PdfParser\Font;
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
    const SUMMARY = 'Summary';
    const EXPERIENCE = 'Experience';
    const SKILLS_EXPERTISE = 'Skills & Expertise';
    const EDUCATION = 'Education';
    const CERTIFICATIONS = 'Certifications';
    const VOLUNTEER_EXPERIENCE = 'Volunteer Experience';
    const LANGUAGES = 'Languages';
    const INTERESTS = 'Interests';
    const ORGANIZATIONS = 'Organizations';
    const COURSES = 'Courses';
    const PROJECTS = 'Projects';
    const HONORS_AND_AWARDS = 'Honors and Awards';

    /**
     * Constants that designate other parts of the resume that don't classify as a section.
     */
    const NAME = 'Name';
    const EMAIL_ADDRESS = 'Email Address';
    const RECOMMENDATIONS = 'Recommendations';

    /**
     * Section titles for each part of the resume
     *
     * @var string[]
     */
    protected $sectionTitles = [
        self::SUMMARY,
        self::EXPERIENCE,
        self::SKILLS_EXPERTISE,
        self::EDUCATION,
        self::CERTIFICATIONS,
        self::VOLUNTEER_EXPERIENCE,
        self::LANGUAGES,
        self::INTERESTS,
        self::ORGANIZATIONS,
        self::COURSES,
        self::PROJECTS,
        self::HONORS_AND_AWARDS
    ];

    /**
     * @param string $filePath
     * @param array  $sections
     * @return ParsedResume
     * @throws FileNotFoundException
     * @throws FileNotReadableException
     * @throws ParseException
     * @throws \Exception
     */
    public function parse(string $filePath, array $sections = []): ParsedResume
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

        if ($this->shouldParseSection(self::NAME, $sections)) {
            $parsedResumeInstance->setName($name);
        }

        list ($textLines, $lastSection) = $this->splitLastSection($textLines, $name);

        if ($this->shouldParseSection(self::EMAIL_ADDRESS, $sections)) {
            if ($emailAddress = $this->getEmailAddress($textLines)) {
                $parsedResumeInstance->setEmailAddress($emailAddress);
            }
        }

        if ($this->shouldParseSection(self::SKILLS_EXPERTISE, $sections)) {
            $skills = $this->getSkills($textLines);
            $parsedResumeInstance->setSkills($skills);
        }

        if ($this->shouldParseSection(self::SUMMARY, $sections)) {
            if ($summary = $this->getSummary($textLines)) {
                $parsedResumeInstance->setSummary($summary);
            }
        }

        if ($this->shouldParseSection(self::EXPERIENCE, $sections)) {
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
        }

        if ($this->shouldParseSection(self::VOLUNTEER_EXPERIENCE, $sections)) {
            $volunteerExperienceEntries = $this->getVolunteerExperienceEntries($textLines);
            $parsedResumeInstance->setVolunteerExperienceEntries($volunteerExperienceEntries);
        }

        if ($this->shouldParseSection(self::EDUCATION, $sections)) {
            $educationEntries = $this->getEducationEntries($textLines);
            $parsedResumeInstance->setEducationEntries($educationEntries);
        }

        if ($this->shouldParseSection(self::CERTIFICATIONS, $sections)) {
            if ($certifications = $this->getCertifications($textLines)) {
                $parsedResumeInstance->setCertifications($certifications);
            }
        }

        if ($this->shouldParseSection(self::LANGUAGES, $sections)) {
            if ($languages = $this->getLanguages($textLines)) {
                $parsedResumeInstance->setLanguages($languages);
            }
        }

        if ($this->shouldParseSection(self::INTERESTS, $sections)) {
            $interests = $this->getInterests($textLines);
            $parsedResumeInstance->setInterests($interests);
        }

        if ($this->shouldParseSection(self::HONORS_AND_AWARDS, $sections)) {
            $honorsAndAwards = $this->getHonorsAndAwards($textLines);
            $parsedResumeInstance->setHonorsAndAwards($honorsAndAwards);
        }

        if ($this->shouldParseSection(self::ORGANIZATIONS, $sections)) {
            $organizations = $this->getOrganizations($textLines);
            $parsedResumeInstance->setOrganizations($organizations);
        }

        if ($this->shouldParseSection(self::COURSES, $sections)) {
            $courses = $this->getCourses($textLines);
            $parsedResumeInstance->setCourses($courses);
        }

        if ($this->shouldParseSection(self::PROJECTS, $sections)) {
            $projects = $this->getProjects($textLines);
            $parsedResumeInstance->setProjects($projects);
        }

        if ($this->shouldParseSection(self::RECOMMENDATIONS, $sections)) {
            $recommendations = $this->getRecommendations($lastSection);
            $parsedResumeInstance->setRecommendations($recommendations);
        }

        return $parsedResumeInstance;
    }

    /**
     * @param string $filePath
     *
     * @return Document
     */
    protected function getParsedPdfInstance(string $filePath): Document
    {
        $pdfParser = new PdfParser();
        return $pdfParser->parseFile($filePath);
    }

    /**
     * @param Document $parsedPdfInstance
     *
     * @return TextLine[]
     * @throws \Exception
     */
    protected function getAllTextLines(Document $parsedPdfInstance): array
    {
        $currentFont = new Font($parsedPdfInstance);

        $textLines = [];

        $libText = [];

        foreach ($parsedPdfInstance->getPages() as $page) {

            $libText = array_merge($libText, $page->getTextArray());

            $content = $page->get('Contents')->getContent();
            $sectionsText = $page->getSectionsText($content);

            foreach ($sectionsText as $section) {

                $commands = $page->getCommandsText($section);

                foreach ($commands as $command) {

                    switch ($command['o']) {
                        case 'Tf':
                            list($id,) = preg_split('/\s/s', $command['c']);
                            $id = trim($id, '/');
                            $currentFont = $page->getFont($id);
                            break;
                        case "'":
                        case 'Tj':
                            $text = $currentFont->decodeText([$command]);
                            $textLines[] = (new TextLine($text, $currentFont));
                            break;
                        case 'TJ':
                            $text = $currentFont->decodeText($command['c']);
                            $textLines[] = (new TextLine($text, $currentFont));
                            break;
                        default:
                    }
                }
            }
        }

        return $textLines;
    }

    /**
     * @param array $textLines
     *
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
     * Check if the given section should be parsed.
     * If no sections specified it is assumed that all sections are to be parsed.
     *
     * @param string $section
     * @param array  $sectionsToParse
     * @return bool
     */
    protected function shouldParseSection($section, array $sectionsToParse)
    {
        return count($sectionsToParse) === 0 || in_array($section, $sectionsToParse);
    }

    /**
     * Check if the given index is indicative of being a Page designation
     * e.g. current index will be "Page" and then the immediate index will be the number
     *
     * @param int   $index
     * @param array $textLines
     *
     * @return bool
     */
    protected function isPageDesignation(int $index, array $textLines): bool
    {
        return (string)$textLines[$index] === 'Page' && is_numeric((string)$textLines[$index + 1]);
    }

    /**
     * @param array    $textLines
     * @param TextLine $name
     * @return array
     */
    protected function splitLastSection(array $textLines, TextLine $name): array
    {
        $lastNameOccurrence = array_search($name, array_reverse($textLines));
        $lastSection = array_splice($textLines, count($textLines) - $lastNameOccurrence - 1);

        return [
            $textLines,
            $lastSection,
        ];
    }

    /**
     * @param TextLine[] $textLines
     *
     * @return string | null
     */
    protected function getEmailAddress(array $textLines)
    {
        /** @var TextLine[] $potentialEmailLines */
        $potentialEmailLines = array_slice($textLines, 1, 4);

        foreach ($potentialEmailLines as $potentialEmailLine) {
            // Very basic email check
            if (preg_match('/.*?\@.*?$/', $potentialEmailLine->getText())) {
                return $potentialEmailLine->getText();
            }
        }

        return null;
    }

    /**
     * @param array $textLines
     *
     * @return array
     */
    protected function getSkills(array $textLines): array
    {
        return $this->getTextValues($this->findSectionLines(self::SKILLS_EXPERTISE, $textLines));
    }

    /**
     * @param array $textLines
     *
     * @return string[]
     */
    protected function getTextValues(array $textLines)
    {
        return array_map(function (TextLine $textLine) {
            return $textLine->getText();
        }, $textLines);
    }

    /**
     * @param string $sectionTitle
     * @param array  $textLines
     *
     * @return TextLine[]
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
     *
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
     *
     * @return string | null
     */
    protected function getSummary(array $textLines)
    {
        $startIndex = array_search(self::SUMMARY, $textLines);

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
     *
     * @return Role[]
     * @throws ParseException
     */
    protected function getRoles(array $textLines): array
    {
        $roleLines = $this->findSectionLines(self::EXPERIENCE, $textLines);
        return $this->buildRoleTypes(Role::class, $roleLines);
    }

    /**
     * @param string     $classType
     * @param TextLine[] $roleLines
     *
     * @return array
     * @throws ParseException
     */
    protected function buildRoleTypes(string $classType, array $roleLines): array
    {
        $roleTypes = [];

        $currentGroupIndex = 0;
        $roleGroups = [];
        $previousLineWasBold = false;

        foreach ($roleLines as $key => $roleLine) {

            $roleLineText = $roleLine->getText();

            if (preg_match('/\s{2}-\s{2}/', $roleLineText)) {
                $previousLineWasBold = false;
                $roleGroups[$currentGroupIndex]['date'] = $roleLineText;
            } elseif ($roleLine->isBold()) {
                if ( ! $previousLineWasBold) {
                    $currentGroupIndex += 1;
                    $roleGroups[$currentGroupIndex] = [
                        'title'   => '',
                        'date'    => '',
                        'summary' => '',
                    ];
                }
                $roleGroups[$currentGroupIndex]['title'] .= ' ' . $roleLineText;
                $previousLineWasBold = true;
            } elseif ( ! preg_match('/\(.*\)/', $roleLineText)) { // This indicates the duration, so skip it.
                $previousLineWasBold = false;
                $roleGroups[$currentGroupIndex]['summary'] .= $roleLineText;
            }
        }

        foreach ($roleGroups as $roleGroup) {
            /** @var RoleInterface $roleType */
            $roleType = new $classType();

            list($title, $organisation) = $this->parseRoleParts($roleGroup['title']);

            $roleType
                ->setTitle($title)
                ->setOrganisation($organisation);

            if ($roleGroup['date']) {
                list($start, $end) = $this->parseDateRange($roleGroup['date'], ' - ');

                $roleType
                    ->setStart($start)
                    ->setEnd($end);
            }

            if ($roleGroup['summary']) {
                $roleType->setSummary($roleGroup['summary']);
            }

            $roleTypes[] = $roleType;
        }

        return $roleTypes;
    }

    /**
     * @param string $roleLine
     *
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
     *
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
     * @param string $delimiter
     *
     * @return array
     * @throws ParseException
     */
    protected function parseDateRange(string $datesLine, string $delimiter): array
    {
        $dateParts = $this->splitAndTrim($delimiter, $datesLine);

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
            throw new ParseException("There was an error parsing the date range from the line '${datesLine}'");
        }
    }

    /**
     * @param string $string
     *
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
     *
     * @return array
     * @throws ParseException
     */
    protected function getVolunteerExperienceEntries(array $textLines): array
    {
        $volunteerExperienceLines = $this->findSectionLines(self::VOLUNTEER_EXPERIENCE, $textLines);
        return $this->buildRoleTypes(VolunteerExperienceEntry::class, $volunteerExperienceLines);
    }

    /**
     * @param array $textLines
     *
     * @return array
     * @throws ParseException
     */
    protected function getEducationEntries(array $textLines): array
    {
        $educationLines = $this->findSectionLines(self::EDUCATION, $textLines);

        $educationEntries = [];

        for ($i = 0; $i < count($educationLines); $i++) {

            $educationLine = $educationLines[$i];

            /** @var EducationEntry $educationEntry */

            if (preg_match('/(.*?)\,\s(.*?)\,\s(\d{4})\s-\s(\d{4})$/', $educationLine, $matches)) { // "Bachelor of Arts, Theatre Management, 2006 - 2010"
                $educationEntry
                    ->setLevel($matches[1])
                    ->setCourseTitle($matches[2])
                    ->setStart($this->parseStringToDateTime($matches[3]))
                    ->setEnd($this->parseStringToDateTime($matches[4]));
            } elseif (preg_match('/(.*?)\,\s(.*?)\,\s(\d{4})$/', $educationLine, $matches)) { // "Bachelor’s Degree, Biomedical Engineering, 2014"
                $educationEntry
                    ->setLevel($matches[1])
                    ->setCourseTitle($matches[2])
                    ->setEnd($this->parseStringToDateTime($matches[3]));
            } elseif (preg_match('/(.*?),\s(\d{4})/', $educationLine, $matches)) { // "High School, 2009"
                $educationEntry
                    ->setLevel($matches[1])
                    ->setEnd($this->parseStringToDateTime($matches[2]));
            } elseif (preg_match('/(\d{4})\s-\s(\d{4})/', $educationLine, $matches)) { // "2002 - 2006"
                $educationEntry
                    ->setStart($this->parseStringToDateTime($matches[1]))
                    ->setEnd($this->parseStringToDateTime($matches[2]));
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
                // If this line doesn't match anything else, it likely marks the start of a new education entry, so add it to the list of entries and start a new one.
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
     * @param array $textLines
     *
     * @return array
     * @throws ParseException
     */
    protected function getCertifications(array $textLines): array
    {
        $certificationLines = $this->findSectionLines(self::CERTIFICATIONS, $textLines);

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
     *
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
     *
     * @return array
     */
    protected function getLanguages(array $textLines): array
    {
        $languageLines = $this->findSectionLines(self::LANGUAGES, $textLines);

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
     *
     * @return string
     */
    protected function getInterests(array $textLines): string
    {
        $interestLines = $this->findSectionLines(self::INTERESTS, $textLines);
        return implode('', $interestLines);
    }

    /**
     * @param array $textLines
     *
     * @return array
     * @throws ParseException
     */
    protected function getOrganizations(array $textLines)
    {
        $organizationLines = $this->findSectionLines(self::ORGANIZATIONS, $textLines);

        $organizations = [];

        $previousLineType = null;

        /** @var Organization $organization */

        foreach ($organizationLines as $organizationLine) {

            $organizationLineText = $organizationLine->getText();

            if ($organizationLine->isBold()) {
                if (isset($organization)) {
                    $organizations[] = $organization;
                }
                $organization = (new Organization())->setName($organizationLineText);
                $previousLineType = 'name';
            } elseif (preg_match('/^\w+\s\d{4}\sto\sPresent$/', $organizationLineText) || preg_match('/^\w+\s\d{4}\sto\s\w+\s\d{4}$/', $organizationLineText)) {
                list($start, $end) = $this->parseDateRange($organizationLineText, ' to ');
                $organization
                    ->setStart($start)
                    ->setEnd($end);
                $previousLineType = 'dates';
            } elseif ($previousLineType === 'name') {
                $organization->setTitle($organizationLineText);
                $previousLineType = 'title';
            } elseif ($previousLineType === 'dates' || $previousLineType == 'summary') {
                $organization->appendSummary($organizationLineText);
                $previousLineType = 'summary';
            } else {
                throw new ParseException("Unable to parse organization line '${organizationLineText}'");
            }
        }

        if (isset($organization)) {
            $organizations[] = $organization;
        }

        return $organizations;
    }

    /**
     * @param array $textLines
     *
     * @return array
     * @throws ParseException
     */
    protected function getHonorsAndAwards(array $textLines)
    {
        $honorsAndAwardsLines = $this->findSectionLines(self::HONORS_AND_AWARDS, $textLines);

        $honorsAndAwards = [];

        $previousLineType = null;

        /** @var HonorAward $honorAward */

        foreach ($honorsAndAwardsLines as $honorsAndAwardsLine) {

            $honorsAndAwardsLineText = $honorsAndAwardsLine->getText();

            if ($honorsAndAwardsLine->isBold()) {
                if (isset($honorAward)) {
                    $honorsAndAwards[] = $honorAward;
                }
                $honorAward = (new HonorAward())->setTitle($honorsAndAwardsLineText);
                $previousLineType = 'title';
            } elseif (preg_match('/^\w{1,}\s\d{4}$/', $honorsAndAwardsLineText)) {
                $honorAward->setDate($this->parseStringToDateTime($honorsAndAwardsLineText));
                $previousLineType = 'date';
            } elseif ($previousLineType === 'title') {
                $honorAward->setInstitution($honorsAndAwardsLineText);
                $previousLineType = 'institution';
            } elseif ($previousLineType === 'date' || $previousLineType == 'summary') {
                $honorAward->appendSummary($honorsAndAwardsLineText);
                $previousLineType = 'summary';
            } else {
                throw new ParseException("Unable to parse honor/award line '${honorsAndAwardsLineText}'");
            }
        }

        if (isset($honorAward)) {
            $honorsAndAwards[] = $honorAward;
        }

        return $honorsAndAwards;
    }

    /**
     * @param array $textLines
     *
     * @return array
     * @throws ParseException
     */
    protected function getCourses(array $textLines)
    {
        $courseLines = $this->findSectionLines(self::COURSES, $textLines);

        $courses = [];

        $previousLineType = null;

        /** @var Course $course */

        foreach ($courseLines as $courseLine) {

            $courseLineText = $courseLine->getText();

            if ($courseLineText === '' || $courseLineText === '.') {
                continue;
            }

            if ($courseLine->isBold()) {
                if ( ! isset($course)) {
                    $course = new Course();
                }

                $course->appendName($courseLineText);

                $previousLineType = 'name';

            } elseif ($previousLineType === 'name') {
                if (isset($course)) {
                    $courses[] = $course;
                }

                $course = new Course();

                $previousLineType = 'misc';
            }
        }

        return $courses;
    }

    /**
     * @param array $textLines
     *
     * @return array
     * @throws ParseException
     */
    protected function getProjects(array $textLines)
    {
        $projectLines = $this->findSectionLines(self::PROJECTS, $textLines);

        $previousLineType = null;

        $projects = [];

        /** @var Project $project */

        foreach ($projectLines as $projectLine) {

            $projectLineText = $projectLine->getText();

            if ($projectLine->isBold() && $previousLineType === 'name') {
                $project->appendName($projectLineText);
                $previousLineType = 'name';
            } elseif ($projectLine->isBold() && $previousLineType !== 'name') {
                if (isset($project)) {
                    $projects[] = $project;
                }

                $project = (new Project())->setName($projectLineText);

                $previousLineType = 'name';
            } elseif (
                preg_match('/^\w+\s\d{4}\sto\sPresent$/', $projectLineText) ||
                preg_match('/^\w+\s\d{4}\sto\s\w+\s\d{4}$/', $projectLineText) ||
                preg_match('/^\d{4}\sto\s\d{4}$/', $projectLineText) ||
                preg_match('/^\d{4}\sto\sPresent$/', $projectLineText)
            ) {
                list($start, $end) = $this->parseDateRange($projectLineText, ' to ');
                $project
                    ->setStart($start)
                    ->setEnd($end);
                $previousLineType = 'dates';
            } elseif (preg_match('/^Members\:(.*)/', $projectLineText, $matches)) {
                $project->setMembers($this->splitAndTrim(',', $matches[1]));
                $previousLineType = 'members';
            } else {
                $project->appendSummary($projectLineText);
                $previousLineType = 'summary';
            }
        }

        if (isset($project)) {
            $projects[] = $project;
        }

        return $projects;
    }

    /**
     * @param array $lastSectionLines
     * @return Recommendation[]
     */
    protected function getRecommendations(array $lastSectionLines)
    {
        $recommendationLines = $this->getRecommendationLines($lastSectionLines);

        if ( ! count($recommendationLines)) {
            return [];
        }

        $previousLineType = null;

        $recommendations = [];

        /** @var Recommendation $recommendation */

        foreach ($recommendationLines as $recommendationLine) {

            $recommendationLineText = $recommendationLine->getText();

            if (preg_match('/^"(.*)\"$/', $recommendationLineText, $matches) || preg_match('/^"(.*)/', $recommendationLineText, $matches)) {
                if (isset($recommendation)) {
                    $recommendations[] = $recommendation;
                }

                $previousLineType = 'summary';

                $recommendation = (new Recommendation())->appendSummary($matches[1]);
            } elseif (preg_match('/\—\s(.*)/', $recommendationLineText, $matches)) {
                $previousLineType = 'name';
                $recommendation->setName($matches[1]);
            } elseif ($recommendationLine->isItalics() && preg_match('/^\,\s(.*)/', $recommendationLineText, $matches)) {
                $previousLineType = 'position';
                $recommendation->setPosition($matches[1]);
            } elseif (($previousLineType === 'position' || $previousLineType === 'name') && preg_match('/^\,\s(.*)/', $recommendationLineText, $matches)) {
                $previousLineType = 'relation';
                $recommendation->setRelation(ucfirst($matches[1]));
            } elseif (preg_match('/(.*)"$/', $recommendationLineText, $matches)) {
                $previousLineType = 'summary';
                $recommendation->appendSummary($matches[1]);
            } else {
                $previousLineType = 'summary';
                $recommendation->appendSummary($recommendationLineText);
            }
        }

        if (isset($recommendation)) {
            $recommendations[] = $recommendation;
        }

        return $recommendations;
    }

    /**
     * @param TextLine[] $lastSectionLines
     * @return TextLine[]
     */
    protected function getRecommendationLines($lastSectionLines)
    {
        // Remove last element because it's always "Contact X on LinkedIn"
        array_pop($lastSectionLines);

        // Find the position of the " person has recommended X" line
        $recommendationStartPosition = false;
        foreach ($lastSectionLines as $index => $lastSectionLine) {
            if (preg_match('/ person has recommended .*/', $lastSectionLine)) {
                $recommendationStartPosition = $index;
            }
        }

        if ($recommendationStartPosition === false) {
            return [];
        }

        return array_slice($lastSectionLines, $recommendationStartPosition + 1);
    }
}