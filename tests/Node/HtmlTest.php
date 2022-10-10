<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Dom\Node\MockNode;
use PHPHtmlParser\Dom\Node\TextNode;
use PHPHtmlParser\Dom\Tag;
use PHPUnit\Framework\TestCase;

class NodeHtmlTest extends TestCase
{
    public function testInnerHtml(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals("<a href='http://google.com'>link</a><br />", $parent->innerHtml());
    }

    public function testInnerHtmlTwice(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childa->setAttribute('href', 'http://google.com', false);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $inner = $parent->innerHtml();
        $this->assertEquals($inner, $parent->innerHtml());
    }

    public function testInnerHtmlUnkownChild(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new MockNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->expectException(\PHPHtmlParser\Exceptions\UnknownChildTypeException::class);
        $parent->innerHtml();
    }

    public function testInnerHtmlMagic(): void
    {
        $parent = new HtmlNode('div');
        $parent->tag->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $childa = new HtmlNode('a');
        $childa->getTag()->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $childbr = new HtmlNode('br');
        $childbr->getTag()->selfClosing();

        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals("<a href='http://google.com'>link</a><br />", $parent->innerHtml);
    }

    public function testOuterHtml(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\'>link</a><br /></div>', $parent->outerHtml());
    }

    public function testOuterHtmlTwice(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $outer = $parent->outerHtml();
        $this->assertEquals($outer, $parent->outerHtml());
    }

    public function testOuterHtmlEmpty(): void
    {
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $node = new HtmlNode($a);

        $this->assertEquals("<a href='http://google.com'></a>", $node->OuterHtml());
    }

    public function testOuterHtmlMagic(): void
    {
        $parent = new HtmlNode('div');
        $parent->getTag()->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $childa = new HtmlNode('a');
        $childa->getTag()->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $childbr = new HtmlNode('br');
        $childbr->getTag()->selfClosing();

        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\'>link</a><br /></div>', $parent->outerHtml);
    }

    public function testOuterHtmlNoValueAttribute(): void
    {
        $parent = new HtmlNode('div');
        $parent->setAttribute('class', 'all');
        $childa = new HtmlNode('a');
        $childa->setAttribute('href', 'http://google.com', false);
        $childa->setAttribute('ui-view', null);
        $childbr = new HtmlNode('br');
        $childbr->getTag()->selfClosing();

        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\' ui-view>link</a><br /></div>', $parent->outerHtml);
    }

    public function testOuterHtmlWithChanges(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\'>link</a><br /></div>', $parent->outerHtml());

        $childa->setAttribute('href', 'https://www.google.com');

        $this->assertEquals('<a href="https://www.google.com">link</a>', $childa->outerHtml());
    }

    public function testText(): void
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);
        $node->addChild(new TextNode('link'));

        $this->assertEquals('link', $node->text());
    }

    public function testTextTwice(): void
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);
        $node->addChild(new TextNode('link'));

        $text = $node->text();
        $this->assertEquals($text, $node->text());
    }

    public function testTextNone(): void
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);

        $this->assertEmpty($node->text());
    }

    public function testTextMagic(): void
    {
        $node = new HtmlNode('a');
        $node->addChild(new TextNode('link'));

        $this->assertEquals('link', $node->text);
    }

    public function testTextLookInChildren(): void
    {
        $p = new HtmlNode('p');
        $a = new HtmlNode('a');
        $a->addChild(new TextNode('click me'));
        $p->addChild(new TextNode('Please '));
        $p->addChild($a);
        $p->addChild(new TextNode('!'));
        $node = new HtmlNode('div');
        $node->addChild($p);

        $this->assertEquals('Please click me!', $node->text(true));
    }

    public function testInnerText(): void
    {
        $node = new HtmlNode('div');
        $node->addChild(new TextNode('123 '));
        $anode = new HtmlNode('a');
        $anode->addChild(new TextNode('456789 '));
        $span_node = new HtmlNode('span');
        $span_node->addChild(new TextNode('101112'));

        $node->addChild($anode);
        $node->addChild($span_node);
        $this->assertEquals($node->innerText, '123 456789 101112');
    }

    public function testTextLookInChildrenAndNoChildren(): void
    {
        $p = new HtmlNode('p');
        $a = new HtmlNode('a');
        $a->addChild(new TextNode('click me'));
        $p->addChild(new TextNode('Please '));
        $p->addChild($a);
        $p->addChild(new TextNode('!'));

        $p->text;
        $p->text(true);

        $this->assertEquals('Please click me!', $p->text(true));
    }

    public function testGetAttribute(): void
    {
        $node = new HtmlNode('a');
        $node->getTag()->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value'       => 'outerlink rounded',
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals('outerlink rounded', $node->getAttribute('class'));
    }

    public function testGetAttributeMagic(): void
    {
        $node = new HtmlNode('a');
        $node->getTag()->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value'       => 'outerlink rounded',
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals('http://google.com', $node->href);
    }

    public function testGetAttributes(): void
    {
        $node = new HtmlNode('a');
        $node->getTag()->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value'       => 'outerlink rounded',
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals('outerlink rounded', $node->getAttributes()['class']);
    }

    public function testSetAttribute(): void
    {
        $node = new HtmlNode('a');
        $node->setAttribute('class', 'foo');
        $this->assertEquals('foo', $node->getAttribute('class'));
    }

    public function testRemoveAttribute(): void
    {
        $node = new HtmlNode('a');
        $node->setAttribute('class', 'foo');
        $node->removeAttribute('class');
        $this->assertnull($node->getAttribute('class'));
    }

    public function testRemoveAllAttributes(): void
    {
        $node = new HtmlNode('a');
        $node->setAttribute('class', 'foo');
        $node->setAttribute('href', 'http://google.com');
        $node->removeAllAttributes();
        $this->assertEquals(0, \count($node->getAttributes()));
    }

    public function testSetTag(): void
    {
        $node = new HtmlNode('div');
        $this->assertEquals('<div></div>', $node->outerHtml());

        $node->setTag('p');
        $this->assertEquals('<p></p>', $node->outerHtml());

        $node->setTag(new Tag('span'));
        $this->assertEquals('<span></span>', $node->outerHtml());
    }

    public function testCountable(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals(\count($parent->getChildren()), \count($parent));
    }

    public function testIterator(): void
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value'       => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value'       => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $children = 0;
        foreach ($parent as $child) {
            ++$children;
        }
        $this->assertEquals(2, $children);
    }

    public function testAncestorByTagFailure(): void
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);
        $this->expectException(\PHPHtmlParser\Exceptions\ParentNotFoundException::class);
        $node->ancestorByTag('div');
    }

    public function testReplaceNode(): void
    {
        $dom = new Dom();
        $dom->loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $id = $dom->find('p')[0]->id();
        $newChild = new HtmlNode('h1');
        $dom->find('p')[0]->getParent()->replaceChild($id, $newChild);
        $this->assertEquals('<div class="all"><h1></h1></div>', (string) $dom);
    }

    public function testTextNodeFirstChild(): void
    {
        $dom = new Dom();
        $dom->loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $p = $dom->find('p');
        foreach ($p as $element) {
            $child = $element->firstChild();
            $this->assertInstanceOf(TextNode::class, $child);
            break;
        }
    }
}
