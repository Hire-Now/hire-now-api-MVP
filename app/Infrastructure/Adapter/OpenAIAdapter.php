<?php

namespace App\Infrastructure\Adapter;

use OpenAI\Laravel\Facades\OpenAI;
use Exception;

class OpenAIAdapter
{
    const MAX_EXECUTION_TIME = 300;
    const CHUNK_SIZE = 3000;
    const MAX_TOKENS = 4096;

    public function extractDataFromText(string $resumeContent, string $language): array
    {
        ini_set('max_execution_time', self::MAX_EXECUTION_TIME);

        $chunks = $this->splitTextIntoChunks($resumeContent);
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
                // Log the error and skip the problematic chunk
                // Consider adding logging here for better error tracking
                continue;
            }
        }

        return $this->mergeResults($results);
    }

    private function splitTextIntoChunks(string $text): array
    {
        // Split the resume content into chunks of a manageable size
        return str_split($text, self::CHUNK_SIZE);
    }

    protected function buildAIExtractionPrompt(string $resumeContent, string $language, string $context): string
    {
        return <<<PROMPT
        Please extract structured data from the following resume content and provide it in a consistent JSON format.
        The output should include two main sections:
        1. **no-enhanced**: The raw, extracted content as it appears in the resume.
        2. **enhanced**: An improved version of the data for SEO purposes, making it more attractive to recruiters.

        JSON output format (Make sure that this structure is completely fulfilled, it is very important, otherwise it will not work on my system):

        {
            "no-enhanced": {
                "professional_summary": "string or null",
                "contact_information": {
                    "email": "string or null",
                    "phone": "string or null",
                    "whatsapp": "string or null",
                    "linkedin": "string or null"
                },
                "work_experience": [
                    {
                        "job_title": "string or null",
                        "company": "string or null",
                        "start_date": "string or null",
                        "end_date": "string or null",
                        "responsibilities": "string or null"
                    }
                ],
                "years_of_experience": "integer or null",
                "education": [
                    {
                        "degree": "string or null",
                        "institution": "string or null",
                        "graduation_date": "string or null"
                    }
                ],
                "skills": ["string or null"],
                "languages": ["string or null"],
                "certifications": [
                    {
                        "certification_name": "string or null",
                        "issued_by": "string or null",
                        "issue_date": "string or null"
                    }
                ],
                "key_projects": [
                    {
                        "project_name": "string or null",
                        "description": "string or null",
                        "technologies_used": "string or null"
                    }
                ],
                "achievements": ["string or null"],
                "portfolio_links": ["string or null"]
            },
            "enhanced": {
                "professional_summary": "string or null",
                "contact_information": {
                    "email": "string or null",
                    "phone": "string or null",
                    "whatsapp": "string or null",
                    "linkedin": "string or null"
                },
                "work_experience": [
                    {
                        "job_title": "string or null",
                        "company": "string or null",
                        "start_date": "string or null",
                        "end_date": "string or null",
                        "responsibilities": "string or null"
                    }
                ],
                "years_of_experience": "integer or null",
                "education": [
                    {
                        "degree": "string or null",
                        "institution": "string or null",
                        "graduation_date": "string or null"
                    }
                ],
                "skills": ["string or null"],
                "languages": ["string or null"],
                "certifications": [
                    {
                        "certification_name": "string or null",
                        "issued_by": "string or null",
                        "issue_date": "string or null"
                    }
                ],
                "key_projects": [
                    {
                        "project_name": "string or null",
                        "description": "string or null",
                        "technologies_used": "string or null"
                    }
                ],
                "achievements": ["string or null"],
                "portfolio_links": ["string or null"]
            }
        }

        Supported languages: en, es, fr, de, it, pt.
        Current resume language: {$language}.

        Format using UTF-8 without special characters ().

        Previous context:
        “”“{$context}”“”

        Resume content:
        “”“{$resumeContent}”“”
    PROMPT;
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

        dd(vars: $finalResult);

        return $finalResult;
    }

    private function mergeArrays(array $array): array
    {
        return array_merge_recursive(...$array);
    }
}
