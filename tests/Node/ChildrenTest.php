<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom\Node\MockNode as Node;
use PHPUnit\Framework\TestCase;

class NodeChildTest extends TestCase
{
    public function testGetParent(): void
    {
        $parent = new Node();
        $child = new Node();
        $child->setParent($parent);
        $this->assertEquals($parent->id(), $child->getParent()->id());
    }

    public function testSetParentTwice(): void
    {
        $parent = new Node();
        $parent2 = new Node();
        $child = new Node();
        $child->setParent($parent);
        $child->setParent($parent2);
        $this->assertEquals($parent2->id(), $child->getParent()->id());
    }

    public function testNextSibling(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child2->id(), $child->nextSibling()->id());
    }

    public function testNextSiblingNotFound(): void
    {
        $parent = new Node();
        $child = new Node();
        $child->setParent($parent);
        $this->expectException(\PHPHtmlParser\Exceptions\ChildNotFoundException::class);
        $child->nextSibling();
    }

    public function testNextSiblingNoParent(): void
    {
        $child = new Node();
        $this->expectException(\PHPHtmlParser\Exceptions\ParentNotFoundException::class);
        $child->nextSibling();
    }

    public function testPreviousSibling(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child->id(), $child2->previousSibling()->id());
    }

    public function testPreviousSiblingNotFound(): void
    {
        $parent = new Node();
        $node = new Node();
        $node->setParent($parent);
        $this->expectException(\PHPHtmlParser\Exceptions\ChildNotFoundException::class);
        $node->previousSibling();
    }

    public function testPreviousSiblingNoParent(): void
    {
        $child = new Node();
        $this->expectException(\PHPHtmlParser\Exceptions\ParentNotFoundException::class);
        $child->previousSibling();
    }

    public function testGetChildren(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child->id(), $parent->getChildren()[0]->id());
    }

    public function testCountChildren(): void
    {
        $parent = new Node();
        $child = new Node();
        $child2 = new Node();
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals(2, $parent->countChildren());
    }

    public function testIsChild(): void
    {
        $parent = new Node();
        $child1 = new Node();
        $child2 = new Node();

        $child1->setParent($parent);
        $child2->setParent($child1);

        $this->assertTrue($parent->isChild($child1->id()));
        $this->assertTrue($parent->isDescendant($child2->id()));
        $this->assertFalse($parent->isChild($child2->id()));
    }
}
