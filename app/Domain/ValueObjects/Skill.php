<?php

namespace App\Domain\ValueObjects;

use App\Domain\Traits\AccessorTrait;
use App\Domain\Attributes\Getter;
use App\Domain\Attributes\Setter;

class Skill
{
    use AccessorTrait;

    public function __construct(
        #[Setter] #[Getter]
        private string $skill,
        #[Setter] #[Getter]
        private string $level
    ) {
    }

    public function equals(Skill $otherSkill): bool
    {
        return $this->skill === $otherSkill->getSkill() && $this->level === $otherSkill->getLevel();
    }
}
