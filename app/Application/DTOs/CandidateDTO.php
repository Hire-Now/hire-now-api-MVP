<?php

namespace App\Application\DTOs;

class CandidateDTO
{
    public function __construct(
        public string $name
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self($data['name']);
    }
}