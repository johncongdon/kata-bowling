<?php

namespace tdd101;

class Frame implements FrameInterface
{
    private $roll1;
    private $roll2;

    public function __construct(int $roll1, int $roll2 = 0)
    {
        if ($roll1 < 0 || $roll2 < 0) {
            throw new \RuntimeException('Rolls must be positive');
        }

        if ($roll1 + $roll2 > 10) {
            throw new \RuntimeException('Too many pins');
        }

        $this->roll1 = $roll1;
        $this->roll2 = $roll2;
    }

    public function getRolls(): array
    {
        return [$this->roll1, $this->roll2];
    }

    public function getBonusRollCount(): int
    {
        if ($this->roll1 === 10) {
            return 2;
        }

        if ($this->roll1 + $this->roll2 === 10) {
            return 1;
        }

        return 0;
    }
}