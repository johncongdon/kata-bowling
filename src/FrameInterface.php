<?php

namespace tdd101;

interface FrameInterface
{
    public function __construct(int $roll1, int $roll2 = 0);
    public function getRolls(): array;
    public function getBonusRollCount(): int;
}