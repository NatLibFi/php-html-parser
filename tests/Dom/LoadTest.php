<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;

class LoadTest extends TestCase
{
    /**
     * @var Dom
     */
    private $dom;

    public function setUp(): void
    {
        $dom = new Dom();
        $dom->loadStr('<div class="all"><br><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></br></div><br class="both" />');
        $this->dom = $dom;
    }

    public function tearDown(): void
    {
        Mockery::close();
    }

    public function testLoadEscapeQuotes(): void
    {
        $a = $this->dom->find('a', 0);
        $this->assertEquals('<a href="google.com" id="78" data-quote="\"">click here</a>', $a->outerHtml);
    }

    public function testLoadNoClosingTag(): void
    {
        $p = $this->dom->find('p', 0);
        $this->assertEquals('Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a>', $p->innerHtml);
    }

    public function testLoadClosingTagOnSelfClosing(): void
    {
        $this->assertCount(2, $this->dom->find('br'));
    }

    public function testIncorrectAccess(): void
    {
        $div = $this->dom->find('div', 0);
        $this->assertEquals(null, $div->foo);
    }

    public function testLoadAttributeOnSelfClosing(): void
    {
        $br = $this->dom->find('br', 1);
        $this->assertEquals('both', $br->getAttribute('class'));
    }

    public function testToStringMagic(): void
    {
        $this->assertEquals('<div class="all"><br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p></div><br class="both" />', (string) $this->dom);
    }

    public function testGetMagic(): void
    {
        $this->assertEquals('<div class="all"><br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p></div><br class="both" />', $this->dom->innerHtml);
    }

    public function testFirstChild(): void
    {
        $this->assertEquals('<div class="all"><br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p></div>', $this->dom->firstChild()->outerHtml);
    }

    public function testLastChild(): void
    {
        $this->assertEquals('<br class="both" />', $this->dom->lastChild()->outerHtml);
    }

    public function testGetElementById(): void
    {
        $this->assertEquals('<a href="google.com" id="78" data-quote="\"">click here</a>', $this->dom->getElementById('78')->outerHtml);
    }

    public function testGetElementsByTag(): void
    {
        $this->assertEquals('<p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p>', $this->dom->getElementsByTag('p')[0]->outerHtml);
    }

    public function testGetElementsByClass(): void
    {
        $this->assertEquals('<br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p>', $this->dom->getElementsByClass('all')[0]->innerHtml);
    }

    public function testDeleteNode(): void
    {
        $a = $this->dom->find('a')[0];
        $a->delete();
        unset($a);
        $this->assertEquals('<div class="all"><br /><p>Hey bro, </p></div><br class="both" />', (string) $this->dom);
    }
}
