<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPHtmlParser\Exceptions\NotLoadedException;
use PHPUnit\Framework\TestCase;

class NotLoadedTest extends TestCase
{
    /**
     * @var Dom
     */
    private $dom;

    public function setUp(): void
    {
        $dom = new Dom();
        $this->dom = $dom;
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testNotLoaded(): void
    {
        $this->expectException(NotLoadedException::class);
        $div = $this->dom->find('div', 0);
    }
}
