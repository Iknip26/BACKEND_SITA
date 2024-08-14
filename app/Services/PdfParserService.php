<?php

namespace App\Services;

use Spatie\PdfToText\Pdf;

class PdfParserService
{
    public function parse($path)
    {
        try {
            // Extract text from the PDF using Spatie's PdfToText package
            $text = (new Pdf())->setPdf($path)->text();

            // Process the text to extract structured data
            $parsedData = $this->extractUserData($text);

            return $parsedData;
        } catch (\Exception $e) {
            throw new \RuntimeException('Failed to parse the PDF file.');
        }
    }

    private function extractUserData($text)
    {
        // Custom logic to extract user data from the text
        // In this example, I'll use regex to extract user information

        // Initialize an array to hold the extracted data
        $userData = [];

        // Regular expression to match user data (adjust this regex based on the actual text structure)
        $pattern = '/(\d+)\s+([a-zA-Z0-9_]+)\s+([a-zA-Z]+)\s+([a-zA-Z]+)\s+([\w.@]+)\s+([\S]+)\s+([\w@]+)\s+([\d-:\s]+)\s+([\d-:\s]+)/';

        // Use preg_match_all to find all matches
        if (preg_match_all($pattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $userData[] = [
                    'id' => $match[1],
                    'username' => $match[2],
                    'first_name' => $match[3],
                    'last_name' => $match[4],
                    'email' => $match[5],
                    'role' => $match[7],
                    'last_login' => $match[8],
                    'created_at' => $match[9],
                    'updated_at' => $match[9]
                ];
            }
        }

        return $userData;
    }
}
