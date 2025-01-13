<?php

namespace App\Application\Handlers\Candidate;

use App\Application\Commands\Candidate\CreateCandidateCommand;
use App\Application\Ports\Inbound\CandidateManagementPort;
use App\Domain\Entities\Candidate;

class CreateCandidateCommandHandler
{
    public function __construct(private CandidateManagementPort $useCase)
    {
    }

    public function handle(CreateCandidateCommand $command): Candidate
    {
        $candidate = new Candidate(null, $command->getUserId());
        $candidate->setSkills($command->getSkills());
        $candidate->setLanguages($command->getSkills());
        $candidate->setPreviousExperiences($command->getPreviousExperiences());
        $candidate->setEducation($command->getEducation());
        $candidate->setProfessionalSummary($command->getProfessionalSummary());
        $candidate->setCertifications($command->getCertifications());
        $candidate->setContactInfo($command->getContactInfo());
        $candidate->setPortfolioLinks($command->getPortfolioLinks());
        $candidate->setPreferences($command->getPreferences());

        return $this->useCase->createCandidate($candidate);
    }
}
