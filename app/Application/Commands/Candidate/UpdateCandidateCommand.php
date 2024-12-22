<?php

namespace App\Application\Commands\Candidate;

class UpdateCandidateCommand
{

    private string $id;
    private string $name;
    private string $email;
    private string $skills;

    public function __construct(string $id, string $name, string $email, string $skills)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->skills = $skills;
    }

    public function getId(): string
    {
        return $this->id;
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
