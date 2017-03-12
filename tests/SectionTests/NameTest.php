<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class NameTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class NameTest extends AbstractSectionTest
{
    public function testAbbyTMiller()
    {
        $result = $this->parsePdf('AbbyTMiller.pdf');
        $this->assertEquals('Abby T. Miller', $result->getName());
    }

    public function testAndrewAng()
    {
        $result = $this->parsePdf('AndrewAng.pdf');
        $this->assertEquals('Andrew Ang', $result->getName());
    }

    public function testAshleyLyle()
    {
        $result = $this->parsePdf('AshleyLyle.pdf');
        $this->assertEquals('Ashley Lyle', $result->getName());
    }

    public function testBrendanWhiting()
    {
        $result = $this->parsePdf('BrendanWhiting.pdf');
        $this->assertEquals('Brendan Whiting', $result->getName());
    }

    public function testErinPellegrino()
    {
        $result = $this->parsePdf('ErinPellegrino.pdf');
        $this->assertEquals('Erin Pellegrino', $result->getName());
    }

    public function testJacobStelman()
    {
        $result = $this->parsePdf('JacobStelman.pdf');
        $this->assertEquals('Jacob Stelman', $result->getName());
    }

    public function testOriRatner()
    {
        $result = $this->parsePdf('OriRatner.pdf');
        $this->assertEquals('Ori Ratner', $result->getName());
    }

    public function testStevenDang()
    {
        $result = $this->parsePdf('StevenDang.pdf');
        $this->assertEquals('Steven Dang', $result->getName());
    }

    public function testTaylorScutti()
    {
        $result = $this->parsePdf('TaylorScutti.pdf');
        $this->assertEquals('Taylor Scutti', $result->getName());
    }

    public function testRossKinsman()
    {
        $result = $this->parsePdf('RossKinsman.pdf');
        $this->assertEquals('Ross Kinsman', $result->getName());
    }

    public function testIsaacMast()
    {
        $result = $this->parsePdf('IsaacMast.pdf');
        $this->assertEquals('Isaac Mast', $result->getName());
    }

    public function testMicahTillman()
    {
        $result = $this->parsePdf('MicahTillman.pdf');
        $this->assertEquals('Micah Tillman', $result->getName());
    }

    public function testSamStoner()
    {
        $result = $this->parsePdf('SamStoner.pdf');
        $this->assertEquals('Sam Stoner', $result->getName());
    }

    public function testDanielMintz()
    {
        $result = $this->parsePdf('DanielMintzLinkedIn.pdf');
        $this->assertEquals('Daniel Mintz', $result->getName());
    }
}