<?php

use PHPUnit\Framework\TestCase;
use tdd101\Frame;

class FrameTest extends TestCase
{
    public function testCanCreateFrame()
    {
        $sut = new Frame(5,0);
        $this->assertEquals([5,0], $sut->getRolls());
    }

    public function testCanNotCreateFrameOver10Pins()
    {
        $this->expectException(RuntimeException::class);
        new Frame(5,6);
    }

    public function testRollsMustBePositive()
    {
        $this->expectException(RuntimeException::class);
        new Frame(-1);
    }

    public function testFrameRequires0BonusRolls()
    {
        $sut = new Frame(5, 0);
        $this->assertEquals(0, $sut->getBonusRollCount());
    }

    public function testStrikeFrameRequires2BonusRolls()
    {
        $sut = new Frame(10, 0);
        $this->assertEquals(2, $sut->getBonusRollCount());
    }

    public function testSpareFrameRequires1BonusRolls()
    {
        $sut = new Frame(9, 1);
        $this->assertEquals(1, $sut->getBonusRollCount());
    }
}