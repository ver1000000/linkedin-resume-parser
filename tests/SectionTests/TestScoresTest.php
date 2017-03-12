<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class TestScoresTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class TestScoresTest extends AbstractSectionTest
{
    public function testJohannChristineAlcaraz()
    {
        $result = $this->parsePdf('JohannChristineAlcaraz.pdf');

        $testScores = $result->getTestScores();
        $firstTestScore = array_shift($testScores);

        $this->assertEquals('SAT', $firstTestScore->getName());
        $this->assertEquals('1980', $firstTestScore->getScore());
    }
}