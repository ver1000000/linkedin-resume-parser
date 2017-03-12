<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class SkillsTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class SkillsTest extends AbstractSectionTest
{
    public function testRossKinsman()
    {
        $skills = $this->parsePdf('RossKinsman.pdf')->getSkills();
        $this->assertContains('PHP', $skills);
        $this->assertContains('Magento', $skills);
        $this->assertContains('Solr', $skills);
        $this->assertContains('Nginx', $skills);
        $this->assertContains('Git', $skills);
    }

    public function testIsaacMast()
    {
        $skills = $this->parsePdf('IsaacMast.pdf')->getSkills();
        $this->assertContains('Ruby', $skills);
        $this->assertContains('AngularJS', $skills);
        $this->assertContains('Bower', $skills);
        $this->assertContains('MySQL', $skills);
        $this->assertContains('PostgreSQL', $skills);
    }

    public function testLucasKaiser()
    {
        $skills = $this->parsePdf('LucasKaiser.pdf')->getSkills();
        $this->assertContains('Matlab', $skills);
        $this->assertContains('C', $skills);
        $this->assertContains('AutoCAD', $skills);
        $this->assertContains('Python', $skills);
        $this->assertContains('Engineering', $skills);
        $this->assertContains('Microsoft Office', $skills);
    }

    public function testJazzyEllis()
    {
        $skills = $this->parsePdf('JazzyEllis.pdf')->getSkills();
        $this->assertEmpty($skills);
    }
}