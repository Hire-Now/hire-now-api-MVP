<?php

namespace App\Application\DTOs\Candidate;

use Exception;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

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
        try {
            return new self(
                $data['name'],
                $data['email'],
                $data['skills']
            );
        } catch (\Throwable $th) {
            throw new BadRequestException("Bad request, all fields are mandatory, please fill in all.");
        }
    }
}
