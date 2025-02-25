<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\TextNode;
use PHPUnit\Framework\TestCase;
use stringEncode\Encode;

class NodeTextTest extends TestCase
{
    public function testText(): void
    {
        $node = new TextNode('foo bar');
        $this->assertEquals('foo bar', $node->text());
    }

    public function testGetTag(): void
    {
        $node = new TextNode('foo bar');
        $this->assertEquals('text', $node->getTag()->name());
    }

    public function testAncestorByTag(): void
    {
        $node = new TextNode('foo bar');
        $text = $node->ancestorByTag('text');
        $this->assertEquals($node, $text);
    }

    public function testPreserveEntity(): void
    {
        $node = new TextNode('&#x69;');
        $text = $node->outerhtml;
        $this->assertEquals('&#x69;', $text);
    }

    public function testIsTextNode(): void
    {
        $node = new TextNode('text');
        $this->assertEquals(true, $node->isTextNode());
    }

    public function testTextInTextNode(): void
    {
        $node = new TextNode('foo bar');
        $this->assertEquals('foo bar', $node->outerHtml());
    }

    public function testSetTextToTextNode(): void
    {
        $node = new TextNode('');
        $node->setText('foo bar');
        $this->assertEquals('foo bar', $node->innerHtml());
    }

    public function testSetText(): void
    {
        $dom = new Dom();
        $dom->loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $a = $dom->find('a')[0];
        $a->firstChild()->setText('biz baz');
        $this->assertEquals('<div class="all"><p>Hey bro, <a href="google.com">biz baz</a><br /> :)</p></div>', (string) $dom);
    }

    public function testSetTextEncoded(): void
    {
        $encode = new Encode();
        $encode->from('UTF-8');
        $encode->to('UTF-8');

        $node = new TextNode('foo bar');
        $node->propagateEncoding($encode);
        $node->setText('biz baz');
        $this->assertEquals('biz baz', $node->text());
    }
}
