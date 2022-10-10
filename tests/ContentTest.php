<?php

declare(strict_types=1);

use PHPHtmlParser\Content;
use PHPHtmlParser\Enum\StringToken;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    public function testChar(): void
    {
        $content = new Content('abcde');
        $this->assertEquals('a', $content->char());
    }

    public function testCharSelection(): void
    {
        $content = new Content('abcde');
        $this->assertEquals('d', $content->char(3));
    }

    public function testFastForward(): void
    {
        $content = new Content('abcde');
        $content->fastForward(2);
        $this->assertEquals('c', $content->char());
    }

    public function testRewind(): void
    {
        $content = new Content('abcde');
        $content->fastForward(2)
                ->rewind(1);
        $this->assertEquals('b', $content->char());
    }

    public function testRewindNegative(): void
    {
        $content = new Content('abcde');
        $content->fastForward(2)
                ->rewind(100);
        $this->assertEquals('a', $content->char());
    }

    public function testCopyUntil(): void
    {
        $content = new Content('abcdeedcba');
        $this->assertEquals('abcde', $content->copyUntil('ed'));
    }

    public function testCopyUntilChar(): void
    {
        $content = new Content('abcdeedcba');
        $this->assertEquals('ab', $content->copyUntil('edc', true));
    }

    public function testCopyUntilEscape(): void
    {
        $content = new Content('foo\"bar"bax');
        $this->assertEquals('foo\"bar', $content->copyUntil('"', false, true));
    }

    public function testCopyUntilNotFound(): void
    {
        $content = new Content('foo\"bar"bax');
        $this->assertEquals('foo\"bar"bax', $content->copyUntil('baz'));
    }

    public function testCopyByToken(): void
    {
        $content = new Content('<a href="google.com">');
        $content->fastForward(3);
        $this->assertEquals('href="google.com"', $content->copyByToken(StringToken::ATTR(), true));
    }

    public function testSkip(): void
    {
        $content = new Content('abcdefghijkl');
        $content->skip('abcd');
        $this->assertEquals('e', $content->char());
    }

    public function testSkipCopy(): void
    {
        $content = new Content('abcdefghijkl');
        $this->assertEquals('abcd', $content->skip('abcd', true));
    }

    public function testSkipByToken(): void
    {
        $content = new Content(' b c');
        $content->fastForward(1);
        $content->skipByToken(StringToken::BLANK());
        $this->assertEquals('b', $content->char());
    }
}
