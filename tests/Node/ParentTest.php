<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom\Node\MockNode as Node;
use PHPUnit\Framework\TestCase;

class NodeParentTest extends TestCase
{
    public function testHasChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $parent->addChild($child);
        $this->assertTrue($parent->hasChildren());
    }

    public function testHasChildNoChildren(): void
    {
        $node = new Node();
        $this->assertFalse($node->hasChildren());
    }

    public function testAddChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $this->assertTrue($parent->addChild($child));
    }

    public function testAddChildTwoParent(): void
    {
        $parent = new Node();
        $parent2 = new Node();
        $child = new Node();
        $parent->addChild($child);
        $parent2->addChild($child);
        $this->assertFalse($parent->hasChildren());
    }

    public function testGetChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);
        $this->assertTrue($parent->getChild($child2->id()) instanceof Node);
    }

    public function testRemoveChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $parent->addChild($child);
        $parent->removeChild($child->id());
        $this->assertFalse($parent->hasChildren());
    }

    public function testRemoveChildNotExists(): void
    {
        $parent = new Node();
        $parent->removeChild(1);
        $this->assertFalse($parent->hasChildren());
    }

    public function testNextChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);

        $this->assertEquals($child2->id(), $parent->nextChild($child->id())->id());
    }

    public function testHasNextChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);

        $this->assertEquals($child2->id(), $parent->hasNextChild($child->id()));
    }

    public function testHasNextChildNotExists(): void
    {
        $parent = new Node();
        $child = new Node();

        $this->expectException(\PHPHtmlParser\Exceptions\ChildNotFoundException::class);
        $parent->hasNextChild($child->id());
    }

    public function testNextChildWithRemove(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $parent->removeChild($child2->id());
        $this->assertEquals($child3->id(), $parent->nextChild($child->id())->id());
    }

    public function testPreviousChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);

        $this->assertEquals($child->id(), $parent->previousChild($child2->id())->id());
    }

    public function testPreviousChildWithRemove(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $parent->removeChild($child2->id());
        $this->assertEquals($child->id(), $parent->previousChild($child3->id())->id());
    }

    public function testFirstChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $this->assertEquals($child->id(), $parent->firstChild()->id());
    }

    public function testLastChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $this->assertEquals($child3->id(), $parent->lastChild()->id());
    }

    public function testInsertBeforeFirst(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child2);
        $parent->addChild($child3);

        $parent->insertBefore($child, $child2->id());

        $this->assertTrue($parent->isChild($child->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function testInsertBeforeLast(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child3);

        $parent->insertBefore($child2, $child3->id());

        $this->assertTrue($parent->isChild($child2->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function testInsertAfterFirst(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child3);

        $parent->insertAfter($child2, $child->id());

        $this->assertTrue($parent->isChild($child2->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function testInsertAfterLast(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);

        $parent->insertAfter($child3, $child2->id());

        $this->assertTrue($parent->isChild($child2->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function testReplaceChild(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child3 = new Node();
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->replaceChild($child->id(), $child3);

        $this->assertFalse($parent->isChild($child->id()));
    }

    public function testSetParentDescendantException(): void
    {
        $parent = new Node();
        $child = new Node();
        $parent->addChild($child);
        $this->expectException(\PHPHtmlParser\Exceptions\CircularException::class);
        $parent->setParent($child);
    }

    public function testAddChildAncestorException(): void
    {
        $parent = new Node();
        $child = new Node();
        $parent->addChild($child);
        $this->expectException(\PHPHtmlParser\Exceptions\CircularException::class);
        $child->addChild($parent);
    }

    public function testAddItselfAsChild(): void
    {
        $parent = new Node();
        $this->expectException(\PHPHtmlParser\Exceptions\CircularException::class);
        $parent->addChild($parent);
    }

    public function testIsAncestorParent(): void
    {
        $parent = new Node();
        $child = new Node();
        $parent->addChild($child);
        $this->assertTrue($child->isAncestor($parent->id()));
    }

    public function testGetAncestor(): void
    {
        $parent = new Node();
        $child = new Node();
        $parent->addChild($child);
        $ancestor = $child->getAncestor($parent->id());
        $this->assertEquals($parent->id(), $ancestor->id());
    }

    public function testGetGreatAncestor(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $parent->addChild($child);
        $child->addChild($child2);
        $ancestor = $child2->getAncestor($parent->id());
        $this->assertNotNull($ancestor);
        $this->assertEquals($parent->id(), $ancestor->id());
    }

    public function testGetAncestorNotFound(): void
    {
        $parent = new Node();
        $ancestor = $parent->getAncestor(1);
        $this->assertNull($ancestor);
    }
}
