<?php

namespace App\Application\DTOs\Candidate;

class CandidateDTO
{
    public string $name;
    public string $email;
    public string $skills;

    // Constructor para inicializar los valores
    public function __construct(string $name, string $email, string $skills)
    {
        $this->name = $name;
        $this->email = $email;
        $this->skills = $skills;
    }

    // Método para convertir los datos de la solicitud a un DTO
    public static function fromRequest(array $data): self
    {
        return new self(
            $data['name'],
            $data['email'],
            $data['skills']
        );
    }
}
