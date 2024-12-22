<?php

namespace App\Application\Commands\Candidate;

class CreateCandidateCommand
{
    private string $name;
    private string $email;
    private string $skills;

    public function __construct(string $name, string $email, string $skills = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->skills = $skills;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSkills(): ?string
    {
        return $this->skills;
    }
}
