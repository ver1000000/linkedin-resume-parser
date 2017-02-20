<?php

namespace LinkedInResumeParser\Tests;

use LinkedInResumeParser\Exception\FileNotFoundException;
use LinkedInResumeParser\ParsedResume;
use LinkedInResumeParser\Parser;
use PHPUnit\Framework\TestCase;

/**
 * Class ParserTest
 *
 * @package LinkedInResumeParser\Tests
 */
class ParserTest extends TestCase
{
    /**
     * @var string
     */
    protected $samplePath;

    public function setUp()
    {
        $this->samplePath = realpath(__DIR__ . '/samples');
        parent::setUp();
    }

    public function testFileNotFound()
    {
        $this->expectException(FileNotFoundException::class);

        $parser = new Parser();
        $parser->parse($this->samplePath . 'ResumeThatDoesNotExist.pdf');
    }

    public function testAllSamples()
    {
        $samplePdfItems = glob($this->samplePath . '/*.pdf');
        $parser = new Parser();

        foreach ($samplePdfItems as $key => $samplePdfItem) {
            $result = $parser->parse($samplePdfItem);
            $this->assertInstanceOf(ParsedResume::class, $result);
        }
    }
}
