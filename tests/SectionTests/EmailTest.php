<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class EmailTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class EmailTest extends AbstractSectionTest
{
    public function testRossKinsman()
    {
        $result = $this->parsePdf('RossKinsman.pdf');
        $this->assertEquals('persata@gmail.com', $result->getEmailAddress());
    }

    public function testIsaacMast()
    {
        $result = $this->parsePdf('IsaacMast.pdf');
        $this->assertEquals('isaac.k.mast@gmail.com', $result->getEmailAddress());
    }

    public function testMicahTillman()
    {
        $result = $this->parsePdf('MicahTillman.pdf');
        $this->assertEquals('micahtillman@gmail.com', $result->getEmailAddress());
    }

    public function testSamStoner()
    {
        $result = $this->parsePdf('SamStoner.pdf');
        $this->assertEquals('samrs116@gmail.com', $result->getEmailAddress());
    }

    public function testDanielMintz()
    {
        $result = $this->parsePdf('DanielMintzLinkedIn.pdf');
        $this->assertEquals('dvm2@georgetown.edu', $result->getEmailAddress());
    }
}