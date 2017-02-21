<?php

namespace LinkedInResumeParser\Pdf;

class TextLine
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var bool
     */
    protected $bold;

    /**
     * TextLine constructor.
     *
     * @param string $text
     * @param bool $bold
     */
    public function __construct(string $text, bool $bold)
    {
        $this->text = $text;
        $this->bold = $bold;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return bool
     */
    public function isBold(): bool
    {
        return $this->bold;
    }
}