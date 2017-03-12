<?php

namespace LinkedInResumeParser\Tests\SectionTests;

/**
 * Class CertificationsTest
 *
 * @package LinkedInResumeParser\Tests\SectionTests
 */
class CertificationsTest extends AbstractSectionTest
{
    public function testRossKinsman()
    {
        $certifications = $this->parsePdf('RossKinsman.pdf')->getCertifications();

        $certification = array_shift($certifications);

        $this->assertEquals('Magento Certified Developer', $certification->getTitle());
        $this->assertEquals('0g0lon00l8', $certification->getLicense());
        $this->assertEquals('Prometric', $certification->getAuthority());
        $this->assertEquals('2015-06-01', $certification->getObtainedOn()->format('Y-m-d'));
    }

    public function testTylerDaluz()
    {
        $certifications = $this->parsePdf('TylerDaluz.pdf')->getCertifications();

        $certification = array_shift($certifications);

        $this->assertEquals('FSA Credential Level II Candidate', $certification->getTitle());
        $this->assertEquals(null, $certification->getLicense());
        $this->assertEquals('SASB – Sustainability Accounting Standards Board', $certification->getAuthority());
        $this->assertEquals('2015-10-01', $certification->getObtainedOn()->format('Y-m-d'));
    }

    public function testPavelGurevich()
    {
        $certifications = $this->parsePdf('PavelGurevich.pdf')->getCertifications();

        $certification = array_shift($certifications);

        $this->assertEquals('Data Manipulation in R with dplyr', $certification->getTitle());
        $this->assertEquals('9b76c21481a7bbab2c27448d37b7b3eb8de697c3', $certification->getLicense());
        $this->assertEquals('DataCamp', $certification->getAuthority());
        $this->assertEquals('2015-09-01', $certification->getObtainedOn()->format('Y-m-d'));
    }

    public function testJohannChristineAlcaraz()
    {
        $certifications = $this->parsePdf('JohannChristineAlcaraz.pdf')->getCertifications();

        $certification = array_shift($certifications);

        $this->assertEquals('Notary Public', $certification->getTitle());
        $this->assertEquals('2133696', $certification->getLicense());
        $this->assertEquals('California Secretary of State', $certification->getAuthority());
        $this->assertEquals('2015-12-01', $certification->getObtainedOn()->format('Y-m-d'));
    }
}