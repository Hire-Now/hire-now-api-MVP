<?php

namespace App\Domain\Entities;

class Candidate
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $skills
    ) {}

    public function getId(): int|null
    {
        return $this->id;
    }

    public function getName(): string|null
    {
        return $this->name;
    }

    public function getEmail(): string|null
    {
        return $this->email;
    }

    public function getSkills(): string|null
    {
        return $this->skills;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setSkills(string $skills): void
    {
        $this->skills = $skills;
    }
}
