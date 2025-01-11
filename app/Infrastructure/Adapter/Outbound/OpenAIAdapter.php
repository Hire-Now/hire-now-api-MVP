<?php

namespace App\Infrastructure\Adapter;

use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class OpenAIAdapter
{
    const MAX_EXECUTION_TIME = 300;
    const CHUNK_SIZE = 3050;
    const MAX_TOKENS = 4096;

    public function extractDataFromText(string $resumeContent, string $language): array
    {
        ini_set('max_execution_time', self::MAX_EXECUTION_TIME);

        $chunks = str_split($resumeContent, self::CHUNK_SIZE);

        $results = [];
        $context = '';

        foreach ($chunks as $chunk) {
            $prompt = $this->buildAIExtractionPrompt($chunk, $language, $context);

            try {
                $completion = OpenAI::chat()->create([
                    'model'      => 'gpt-3.5-turbo',
                    'messages'   => [ [ 'role' => 'user', 'content' => $prompt ] ],
                    'max_tokens' => self::MAX_TOKENS,
                ]);

                $currentResult = $completion->choices[0]->message->content;
                $results[] = $currentResult;
                $context .= $currentResult . "\n";

            } catch (Exception $e) {
                continue;
            }
        }

        return $this->mergeResults($results);
    }

    protected function buildAIExtractionPrompt(string $resumeContent, string $language, string $context): string
    {
        return "Por favor, extrae datos estructurados del siguiente contenido de currículum y proporciona la información en un formato JSON consistente y preciso. El resultado debe incluir dos secciones principales:\n\n1. **no-enhanced**: El contenido bruto, extraído tal como aparece en el currículum.\n2. **enhanced**: Una versión mejorada de los datos con fines de SEO, haciéndola más atractiva para los reclutadores.\n\nFormato de salida del JSON (Asegúrate de cumplir exactamente con esta estructura, es crucial para que funcione en mi sistema):\n\njson\n{\n    \"no-enhanced\": { \n        \"professional_summary\": \"string or null\",\n        \"contact_information\": {\n            \"email\": \"string or null\",\n            \"phone\": \"string or null\",\n            \"whatsapp\": \"string or null\",\n            \"linkedin\": \"string or null\"\n        },\n        \"work_experience\": [\n            {\n                \"job_title\": \"string or null\",\n                \"company\": \"string or null\",\n                \"start_date\": \"string or null\",\n                \"end_date\": \"string or null\",\n                \"responsibilities\": \"string or null\"\n            }\n        ],\n        \"years_of_experience\": \"integer or null\",\n        \"education\": [\n            {\n                \"degree\": \"string or null\",\n                \"institution\": \"string or null\",\n                \"graduation_date\": \"string or null\"\n            }\n        ],\n        \"skills\": [\"string or null\"],\n        \"languages\": [\"string or null\"],\n        \"certifications\": [\n            {\n                \"certification_name\": \"string or null\",\n                \"issued_by\": \"string or null\",\n                \"issue_date\": \"string or null\"\n            }\n        ],\n        \"key_projects\": [\n            {\n                \"project_name\": \"string or null\",\n                \"description\": \"string or null\",\n                \"technologies_used\": \"string or null\"\n            }\n        ],\n        \"achievements\": [\"string or null\"],\n        \"portfolio_links\": [\"string or null\"]\n    },\n    \"enhanced\": {\n        \"professional_summary\": \"string or null\",\n        \"contact_information\": {\n            \"email\": \"string or null\",\n            \"phone\": \"string or null\",\n            \"whatsapp\": \"string or null\",\n            \"linkedin\": \"string or null\"\n        },\n        \"work_experience\": [\n            {\n                \"job_title\": \"string or null\",\n                \"company\": \"string or null\",\n                \"start_date\": \"string or null\",\n                \"end_date\": \"string or null\",\n                \"responsibilities\": \"string or null\"\n            }\n        ],\n        \"years_of_experience\": \"integer or null\",\n        \"education\": [\n            {\n                \"degree\": \"string or null\",\n                \"institution\": \"string or null\",\n                \"graduation_date\": \"string or null\"\n            }\n        ],\n        \"skills\": [\"string or null\"],\n        \"languages\": [\"string or null\"],\n        \"certifications\": [\n            {\n                \"certification_name\": \"string or null\",\n                \"issued_by\": \"string or null\",\n                \"issue_date\": \"string or null\"\n            }\n        ],\n        \"key_projects\": [\n            {\n                \"project_name\": \"string or null\",\n                \"description\": \"string or null\",\n                \"technologies_used\": \"string or null\"\n            }\n        ],\n        \"achievements\": [\"string or null\"],\n        \"portfolio_links\": [\"string or null\"]\n    }\n}\n\nAsegúrate de que los datos que ingreses en la sección **no-enhanced** se mantengan fieles a su apariencia en el currículum. En la sección **enhanced**, mejora los datos tanto como sea posible para hacerlos más atractivos para los reclutadores.\n\nIdiomas soportados: en, es, fr, de, it, pt.\nIdioma actual del currículum: {$language}.\n\nFormatea utilizando UTF-8 sin caracteres especiales.\n\nContexto previo:\njson\n\"{$context}\"\n\nContenido del currículum:\njson\n\"{$resumeContent}\"";
    }

    private function mergeResults(array $results): array
    {
        $finalResult = [
            'no-enhanced' => [],
            'enhanced'    => []
        ];

        foreach ($results as $result) {
            $decoded = json_decode($result, true);
            if ($decoded) {
                foreach ([ 'no-enhanced', 'enhanced' ] as $key) {
                    if (isset($decoded[$key])) {
                        $finalResult[$key][] = $decoded[$key];
                    }
                }
            }
        }

        $finalResult['no-enhanced'] = $this->mergeArrays($finalResult['no-enhanced']);
        $finalResult['enhanced'] = $this->mergeArrays($finalResult['enhanced']);

        return $finalResult;
    }

    private function mergeArrays(array $array): array
    {
        return array_merge_recursive(...$array);
    }
}
