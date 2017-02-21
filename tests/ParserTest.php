<?php

namespace LinkedInResumeParser\Tests;

use LinkedInResumeParser\Exception\FileNotFoundException;
use LinkedInResumeParser\Exception\LinkedInResumeParserException;
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
        $parser->parse($this->samplePath . '/ResumeThatDoesNotExist.pdf');
    }

    public function testSingleSample()
    {
        $parser = new Parser();
        $result = $parser->parse($this->samplePath . '/ElisseJean-Pierre.pdf');
        $this->assertInstanceOf(ParsedResume::class, $result);
    }

    public function testAllSamples()
    {
        $samplePdfItems = glob($this->samplePath . '/*.pdf');
        $parser = new Parser();

        foreach ($samplePdfItems as $key => $samplePdfItem) {
            echo "Running ${samplePdfItem}" . PHP_EOL;
            $result = $parser->parse($samplePdfItem);
            print_r(json_encode($result, JSON_PRETTY_PRINT));
            $this->assertInstanceOf(ParsedResume::class, $result);
        }
    }
}
