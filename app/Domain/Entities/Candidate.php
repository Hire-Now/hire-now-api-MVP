<?php

namespace App\Domain\Entities;

class Candidate
{
    public function __construct(
        public ?int $id,
        public string $name,
        public string $email,
        public string $skills
    ) {}
}
