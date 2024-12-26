<?php

namespace App\Domain\Services;

use App\Domain\Entities\Candidate;
use App\Domain\Repositories\CandidateRepositoryInterface;

class CandidateService
{
    public function __construct(private CandidateRepositoryInterface $repository)
    {}

    //todo: implementar validaciones para checkear skills y otros valores (todo lo relacionado a reglas de negocio SOLO INTERACTUA CON LOS DATOS)
}
