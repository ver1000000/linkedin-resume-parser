<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class NameTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class NameTest extends AbstractSectionTest
{
    public function testAakashGupta()
    {
        $result = $this->parsePdf('AakashGuptaProfile.pdf');
        $this->assertEquals('Aakash Gupta', $result->getName());
    }
}
