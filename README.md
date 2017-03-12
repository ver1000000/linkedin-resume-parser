# LinkedIn Resume Parser

[![Build Status](https://travis-ci.org/Persata/linkedin-resume-parser.svg?branch=master)](https://travis-ci.org/Persata/linkedin-resume-parser)

This library parses LinkedIn PDF resumes into a set of PHP entities. The entities are based on the various sections
found in each resume, making it easy to extract information such as current / previous roles, education history,
certifications etc.

## Basic Use

```php
$resumeParser = new \LinkedInResumeParser\Parser();
$parsedResume = $resumeParser->parse('/path/to/resume.pdf');

echo $parsedResume->getName();
// Ross Kinsman

echo $parsedResume->getCurrentRole()->getOrganisation();
// The Drum

echo $parsedResume->getCurrentRole()->getStart()->format('F, Y');
// August, 2016

foreach ($parsedResume->getSkills() as $skill) {
    echo $skill;
    // PHP
    // Git
    // ...
}

foreach ($parsedResume->getEducationEntries() as $educationEntry) {
    echo $educationEntry->getInstitution();
    // University of Strathclyde
}
```

## Test Data

More test data would always be appreciated, so if you wish to include your resume I'll gladly accept PRs and
relevant tests. 

The existing test data for this project is a collection of LinkedIn resumes I found on GitHub. If your resume is 
here, and you wish to have it removed, I'm easily reachable on Twitter at [@persata](https://twitter.com/persata),
or open an issue on this repository.