<?php

namespace LinkedInResumeParser\Pdf;

use Smalot\PdfParser\Font;

/**
 * Class TextLine
 *
 * @package LinkedInResumeParser\Pdf
 */
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
     * @var Font
     */
    protected $font;

    /**
     * TextLine constructor.
     *
     * @param string $text
     * @param Font   $font
     */
    public function __construct(string $text, Font $font)
    {
        $this->font = $font;
        $this->bold = $this->isFontBold($font);
        $this->text = $text;
    }

    /**
     * @param Font $font
     * @return bool
     */
    protected function isFontBold(Font $font): bool
    {
        $fontName = $font->get('BaseFont')->getContent();
        return stripos($fontName, 'bold') !== false;
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