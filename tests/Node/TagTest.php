<?php

declare(strict_types=1);

use PHPHtmlParser\Dom\Tag;
use PHPUnit\Framework\TestCase;

class NodeTagTest extends TestCase
{
    public function testSelfClosing(): void
    {
        $tag = new Tag('a');
        $tag->selfClosing();
        $this->assertTrue($tag->isSelfClosing());
    }

    public function testSetAttributes(): void
    {
        $attr = [
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function testRemoveAttribute(): void
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $tag->removeAttribute('href');
        $this->expectException(\PHPHtmlParser\Exceptions\Tag\AttributeNotFoundException::class);
        $tag->getAttribute('href');
    }

    public function testRemoveAllAttributes(): void
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $tag->setAttribute('class', 'clear-fix', true);
        $tag->removeAllAttributes();
        $this->assertEquals(0, \count($tag->getAttributes()));
    }

    public function testSetAttributeNoArray(): void
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function testSetAttributesNoDoubleArray(): void
    {
        $attr = [
            'href'  => 'http://google.com',
            'class' => 'funtimes',
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('funtimes', $tag->getAttribute('class')->getValue());
    }

    public function testUpdateAttributes(): void
    {
        $tag = new Tag('a');
        $tag->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value'       => null,
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals(null, $tag->getAttribute('class')->getValue());
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());

        $attr = [
            'href'  => 'https://www.google.com',
            'class' => 'funtimes',
        ];

        $tag->setAttributes($attr);
        $this->assertEquals('funtimes', $tag->getAttribute('class')->getValue());
        $this->assertEquals('https://www.google.com', $tag->getAttribute('href')->getValue());
    }

    public function testNoise(): void
    {
        $tag = new Tag('a');
        $this->assertTrue($tag->noise('noise') instanceof Tag);
    }

    public function testGetAttributeMagic(): void
    {
        $attr = [
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function testSetAttributeMagic(): void
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function testMakeOpeningTag(): void
    {
        $attr = [
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => true,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('<a href="http://google.com">', $tag->makeOpeningTag());
    }

    public function testMakeOpeningTagEmptyAttr(): void
    {
        $attr = [
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => true,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $tag->setAttribute('selected', null);
        $this->assertEquals('<a href="http://google.com" selected>', $tag->makeOpeningTag());
    }

    public function testMakeOpeningTagSelfClosing(): void
    {
        $attr = [
            'class' => [
                'value'       => 'clear-fix',
                'doubleQuote' => true,
            ],
        ];

        $tag = (new Tag('div'))
            ->selfClosing()
            ->setAttributes($attr);
        $this->assertEquals('<div class="clear-fix" />', $tag->makeOpeningTag());
    }

    public function testMakeClosingTag(): void
    {
        $tag = new Tag('a');
        $this->assertEquals('</a>', $tag->makeClosingTag());
    }

    public function testMakeClosingTagSelfClosing(): void
    {
        $tag = new Tag('div');
        $tag->selfClosing();
        $this->assertEmpty($tag->makeClosingTag());
    }

    public function testSetTagAttribute(): void
    {
        $tag = new Tag('div');
        $tag->setStyleAttributeValue('display', 'none');
        $this->assertEquals('display:none;', $tag->getAttribute('style')->getValue());
    }

    public function testGetStyleAttributesArray(): void
    {
        $tag = new Tag('div');
        $tag->setStyleAttributeValue('display', 'none');
        $this->assertIsArray($tag->getStyleAttributeArray());
    }
}
