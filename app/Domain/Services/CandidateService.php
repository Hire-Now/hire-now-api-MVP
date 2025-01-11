<?php

namespace App\Domain\Services;

use App\Domain\Entities\Candidate;
use App\Domain\Repositories\ICandidateRepository;

class CandidateService
{
    public function calculateYearsOfExperienceWithMonths(int|float $monthsOfExperience)
    {
        $yearsOfExperience = intdiv($monthsOfExperience, 12);
        $monthsOfExperience = $monthsOfExperience % 12;

        return [
            $yearsOfExperience,
            $monthsOfExperience
        ];
    }

    /**
     * Procesa un array de datos, valida rangos de fechas y realiza cálculos (como la experiencia) según las claves configurables.
     *
     * @param array $data Los datos a procesar.
     * @param array $dateKeys Las claves dentro del array que contienen fechas para validar.
     * @param string|null $experienceKey La clave que se utilizará para acumular la experiencia en meses (opcional).
     * @param callable|null $validationCallback Función opcional para validaciones personalizadas.
     * @return array Datos procesados.
     * @throws \RuntimeException Si alguna validación falla.
     */
    public static function validateDatesAndExtractExperience(array $data, ?string $experienceKey = null): array
    {
        $monthsOfExperience = [];

        $processedData = collect($data)->map(function ($value, $key) use (&$monthsOfExperience) {
            if (is_array($value)) {
                foreach ($value as &$entry) {
                    if (isset($entry['start_date']) && isset($entry['end_date'])) {
                        $startDate = $entry['start_date'];
                        $endDate = $entry['end_date'];

                        if ($endDate->lessThan($startDate)) {
                            throw new \RuntimeException("La fecha de fin debe ser mayor que la fecha de inicio para el campo $key.");
                        }

                        if ($startDate->greaterThan($endDate)) {
                            throw new \RuntimeException("La fecha de inicio debe ser inferior que la fecha de fin para el campo $key.");
                        }

                        if ($key === 'previous_experiences') {
                            $monthsOfExperience[] = $startDate->diffInMonths($endDate);
                        }
                    }
                }
            }

            return $value;
        })->toArray();

        return [
            'data'                 => $processedData,
            'months_of_experience' => array_sum($monthsOfExperience),
        ];
    }

}
