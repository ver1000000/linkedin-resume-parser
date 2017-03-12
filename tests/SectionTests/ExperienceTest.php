<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class ExperienceTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class ExperienceTest extends AbstractSectionTest
{
    public function testEdwinAmirian()
    {
        $result = $this->parsePdf('RossKinsman.pdf');

        $currentRole = $result->getCurrentRole();

        $this->assertEquals('Senior Server Side Developer', $currentRole->getTitle());
        $this->assertEquals('Houseology.', $currentRole->getOrganisation());
        $this->assertEquals('2015-06-01', $currentRole->getStart()->format('Y-m-d'));
        $this->assertNull($currentRole->getEnd());
    }

    public function testJacobStelman()
    {
        $result = $this->parsePdf('JacobStelman.pdf');

        $currentRole = $result->getCurrentRole();
        $previousRoles = $result->getPreviousRoles();

        $this->assertEquals('Hardware Engineer, Loon, Google [X]', $currentRole->getTitle());
        $this->assertEquals('Google', $currentRole->getOrganisation());
        $this->assertEquals('Loon', $currentRole->getSummary());
        $this->assertEquals('2016-06-01', $currentRole->getStart()->format('Y-m-d'));
        $this->assertNull($currentRole->getEnd());

        $firstPreviousRole = array_shift($previousRoles);

        $this->assertEquals('Control Systems Engineer', $firstPreviousRole->getTitle());
        $this->assertEquals('Facebook', $firstPreviousRole->getOrganisation());
        $this->assertContains('Summary: Made extensive use of cloud computing to perform computationally intensive analyses on large data sets regarding topics for which I had little or no prior knowledge.', $firstPreviousRole->getSummary());
        $this->assertContains('#Designed custom gimbal to give payload required field of view, a custom raydome as well as custom aircraft panel to house payload.', $firstPreviousRole->getSummary());
        $this->assertEquals('2015-06-01', $firstPreviousRole->getStart()->format('Y-m-d'));
        $this->assertEquals('2015-09-01', $firstPreviousRole->getEnd()->format('Y-m-d'));
    }

    public function testLeahKovach()
    {
        $result = $this->parsePdf('LeahKovach.pdf');

        $currentRole = $result->getCurrentRole();
        $previousRoles = $result->getPreviousRoles();

        $this->assertEquals('Marketing Specialist - Demand Generation', $currentRole->getTitle());
        $this->assertEquals('Movable Ink', $currentRole->getOrganisation());
        $this->assertContains('- Manage demand generation projects with key stakeholders across multiple departments, including the company referral program', $currentRole->getSummary());
        $this->assertEquals('2016-03-01', $currentRole->getStart()->format('Y-m-d'));
        $this->assertNull($currentRole->getEnd());

        $firstPreviousRole = array_shift($previousRoles);

        $this->assertEquals('Marketing Coordinator - Demand Generation', $firstPreviousRole->getTitle());
        $this->assertEquals('Movable Ink', $firstPreviousRole->getOrganisation());
        $this->assertEquals('As the first-ever marketing coordinator, I took the lead on Movable Ink\'s social media presence and managed several content marketing / conference initiatives.', $firstPreviousRole->getSummary());
        $this->assertEquals('2015-06-01', $firstPreviousRole->getStart()->format('Y-m-d'));
        $this->assertEquals('2016-03-01', $firstPreviousRole->getEnd()->format('Y-m-d'));
    }

    public function testMichelleGeng()
    {
        $result = $this->parsePdf('MichelleGeng.pdf');

        $currentRole = $result->getCurrentRole();
        $previousRoles = $result->getPreviousRoles();

        $this->assertEquals('Firm Strategy & Execution Analyst', $currentRole->getTitle());
        $this->assertEquals('Morgan Stanley', $currentRole->getOrganisation());
        $this->assertNull($currentRole->getSummary());
        $this->assertEquals('2016-07-01', $currentRole->getStart()->format('Y-m-d'));
        $this->assertNull($currentRole->getEnd());

        $firstPreviousRole = array_shift($previousRoles);

        $this->assertEquals('Apprentice', $firstPreviousRole->getTitle());
        $this->assertEquals('AdmitHub', $firstPreviousRole->getOrganisation());
        $this->assertEquals('Education technology start-up using artificial intelligence (machine learning and natural language processing) and chatbots to help universities optimize their enrollment management', $firstPreviousRole->getSummary());
        $this->assertEquals('2016-02-01', $firstPreviousRole->getStart()->format('Y-m-d'));
        $this->assertEquals('2016-05-01', $firstPreviousRole->getEnd()->format('Y-m-d'));
    }

    public function testSophiaFeng()
    {
        $result = $this->parsePdf('SophiaFeng.pdf');

        $currentRole = $result->getCurrentRole();
        $previousRoles = $result->getPreviousRoles();

        $this->assertEquals('Software Engineer', $currentRole->getTitle());
        $this->assertEquals('Pinterest', $currentRole->getOrganisation());
        $this->assertNull($currentRole->getSummary());
        $this->assertEquals('2015-04-01', $currentRole->getStart()->format('Y-m-d'));
        $this->assertNull($currentRole->getEnd());

        $firstPreviousRole = array_shift($previousRoles);

        $this->assertEquals('Entrepreneurship for Computer Science Teaching Assistant', $firstPreviousRole->getTitle());
        $this->assertEquals('Carnegie Mellon University', $firstPreviousRole->getOrganisation());
        $this->assertNull($firstPreviousRole->getSummary());
        $this->assertEquals('2014-08-01', $firstPreviousRole->getStart()->format('Y-m-d'));
        $this->assertEquals('2014-12-01', $firstPreviousRole->getEnd()->format('Y-m-d'));
    }
}