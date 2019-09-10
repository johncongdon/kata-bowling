<?php
namespace tdd101;

class Game
{

    protected $score = 0;

    /**
     * @var FrameInterface[]
     */
    protected $frames = [];

    public function getScore()
    {
        $score = 0;
        foreach ($this->frames as $index => $frame) {
            $frameNumber = $index + 1;
            if ($frameNumber > 10) {
                continue;
            }
            [$roll1, $roll2] = $frame->getRolls();
            $bonus = 0;
            $bonusRolls = $frame->getBonusRollCount();
            if ($bonusRolls === 1) {
                [$bonusRoll1, $bonusRoll2] = $this->frames[$index+1]->getRolls();
                $bonus = $bonusRoll1;
            }

            if ($bonusRolls === 2) {
                [$bonusRoll1, $bonusRoll2] = $this->frames[$index+1]->getRolls();
                if ($this->frames[$index+1]->getBonusRollCount() === 2) {
                    [$bonusRoll2] = $this->frames[$index+2]->getRolls();
                }
                $bonus = $bonusRoll1 + $bonusRoll2;
            }
            $score += $roll1 + $roll2 + $bonus;
        }
        return $score;
    }

    public function addFrame(FrameInterface $frame)
    {
        $this->frames[] = $frame;
    }

    public function gameComplete()
    {
        if (count($this->frames) < 10) {
            return false;
        }

        $frame10 = $this->frames[9];
        $frame11 = $this->frames[10];
        $totalFramesNeeded = 10;
        if ($frame10->getBonusRollCount() === 2 && $frame11->getBonusRollCount() === 2) {
            $totalFramesNeeded = 12;
        } else if ($frame10->getBonusRollCount()) {
            $totalFramesNeeded = 11;
        }

        return count($this->frames) === $totalFramesNeeded;
    }
}