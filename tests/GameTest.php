<?php

use PHPUnit\Framework\TestCase;
use tdd101\FrameInterface;
use tdd101\Game;

class GameTest extends TestCase
{
    public function testCanInstantiateGame()
    {
        $sut = new Game;

        $this->assertIsObject($sut);
    }

    public function testCanGetScore()
    {
        $sut = new Game;

        $this->assertEquals(0, $sut->getScore());
    }

    public function testCanAddRoll()
    {
        $sut = new Game;

        $frame = $this->prophesize(FrameInterface::class);
        $frame->getRolls()->willReturn([5,0]);
        $frame->getBonusRollCount()->willReturn(0);

        $sut->addFrame($frame->reveal());

        $this->assertEquals(5, $sut->getScore());
    }

    public function testCanAdd2Frames()
    {
        $sut = new Game;

        $frame = $this->prophesize(FrameInterface::class);
        $frame->getRolls()->willReturn([5,0]);
        $frame->getBonusRollCount()->willReturn(0);
        $sut->addFrame($frame->reveal());

        $frame = $this->prophesize(FrameInterface::class);
        $frame->getRolls()->willReturn([6,0]);
        $frame->getBonusRollCount()->willReturn(0);
        $sut->addFrame($frame->reveal());

        $this->assertEquals(11, $sut->getScore());
    }

    public function testCanAddFramesFromDataProvider()
    {
        $sut = new Game;

        foreach ($this->multipleFrameProvider() as $frameData) {
            $frame = $this->prophesize(FrameInterface::class);
            $frame->getRolls()->willReturn([$frameData[0], $frameData[1]]);
            $frame->getBonusRollCount()->willReturn($frameData[2]);
            $sut->addFrame($frame->reveal());
        }

        $this->assertEquals(15, $sut->getScore());
    }

    public function testGameIsNotCompleteWith0Frames()
    {
        $sut = new Game;
        $this->assertFalse($sut->gameComplete());
    }

    public function testGameIsNotCompleteWith1Frame()
    {
        $sut = new Game;
        $frame = $this->prophesize(FrameInterface::class);
        $frame->getRolls()->willReturn([10,0]);
        $this->assertFalse($sut->gameComplete());
    }

    public function testGameCompleteWith10Frames()
    {
        $sut = new Game;
        $this->assertFalse($sut->gameComplete());

        foreach ($this->multipleFrameProvider() as $frameData) {
            $frame = $this->prophesize(FrameInterface::class);
            $frame->getRolls()->willReturn($frameData);
            $sut->addFrame($frame->reveal());
        }

        foreach ($this->multipleFrameProvider() as $frameData) {
            $frame = $this->prophesize(FrameInterface::class);
            $frame->getRolls()->willReturn($frameData);
            $frame->getBonusRollCount()->willReturn(0);
            $sut->addFrame($frame->reveal());
        }

        $this->assertTrue($sut->gameComplete());
    }

    public function testGameCompleteAfterPerfectGame()
    {
        $sut = new Game;
        $this->assertFalse($sut->gameComplete());

        foreach ($this->perfectGameProvider() as $frameData) {
            $frame = $this->prophesize(FrameInterface::class);
            $frame->getRolls()->willReturn($frameData);
            $frame->getBonusRollCount()->willReturn(2);
            $sut->addFrame($frame->reveal());
        }

        $this->assertTrue($sut->gameComplete());
        $this->assertEquals(300, $sut->getScore());
    }

    public function testGameCompleteAfterNineSpareStrikeGame()
    {
        $sut = new Game;
        $this->assertFalse($sut->gameComplete());

        foreach ($this->nineSpareStrikeGameProvider() as $frameData) {
            $frame = $this->prophesize(FrameInterface::class);
            $frame->getRolls()->willReturn([$frameData[0], $frameData[1]]);
            $frame->getBonusRollCount()->willReturn($frameData[2]);
            $sut->addFrame($frame->reveal());
        }

        $this->assertTrue($sut->gameComplete());
        $this->assertEquals(199, $sut->getScore());
    }

    public function testGameCompleteAfterNineSpareGame()
    {
        $sut = new Game;
        $this->assertFalse($sut->gameComplete());

        foreach ($this->nineSpareGameProvider() as $frameData) {
            $frame = $this->prophesize(FrameInterface::class);
            $frame->getRolls()->willReturn($frameData);
            $frame->getBonusRollCount()->willReturn(1);
            $sut->addFrame($frame->reveal());
        }

        $this->assertTrue($sut->gameComplete());
        $this->assertEquals(190, $sut->getScore());
    }

    protected function multipleFrameProvider()
    {
        yield [1,0,0];
        yield [2,0,0];
        yield [3,0,0];
        yield [4,0,0];
        yield [5,0,0];
    }

    protected function nineSpareGameProvider()
    {
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
        yield [9,1];
    }

    protected function perfectGameProvider()
    {
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
        yield [10,0];
    }

    protected function nineSpareStrikeGameProvider()
    {
        yield [9,1,1]; //1
        yield [10,0,2]; //2
        yield [9,1,1]; //3
        yield [10,0,2]; //4
        yield [9,1,1]; //5
        yield [10,0,2]; //6
        yield [9,1,1]; //7
        yield [10,0,2]; //8
        yield [9,1,1]; //9
        yield [10,0,2]; //10
        yield [9,0,0]; //10
    }
}