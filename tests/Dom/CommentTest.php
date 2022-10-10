<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    /**
     * Data provider for testComment.
     *
     */
    public function getTestCommentData(): array
    {
        return [
            ['<!-- test comment with number 2 -->'],
            ['<!--<div><img />-->'],
            ['<!--[if (gte IE 6)&(lte IE 8)]><script src="https://www.random.test.js" /></script><![endif]-->'],
        ];
    }

    /**
     * Test comment handling.
     *
     * @dataProvider getTestCommentData
     *
     * @param mixed $comment
     */
    public function testComment($comment): void
    {
        $dom = new Dom();
        $options = new Options();
        $options->setCleanupInput(false);
        $dom->loadStr($comment, $options);
        $this->assertEquals($comment, $dom->innerHtml);
    }
}
