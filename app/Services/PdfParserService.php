<?php

namespace App\Services;

use Spatie\PdfToText\Pdf;
use Exception;

class PdfParserService
{
    protected $pdfToTextPath;

    public function __construct()
    {
        $this->pdfToTextPath = env('PDFTOTEXT_PATH', 'C:\Users\Fabih\AppData\Local\poppler-24.07.0\Library\bin\pdftotext.exe');
    }
    public function parse($pdfPath)
    {
        try {
            // dd($pdfPath);
            $data = Pdf::getText($pdfPath,$this->pdfToTextPath);
            // $text = Pdf::getText($pdfPath);

            // Here you need to parse the text to extract the relevant data
            // $data = [];
            // if (preg_match('/Name:\s*(.+)/', $text, $matches)) {
            //     $data['name'] = trim($matches[1]);
            // }
            // if (preg_match('/NIM:\s*(\d+)/', $text, $matches)) {
            //     $data['NIM'] = trim($matches[1]);
            // }
            // // Add more parsing logic as needed

            return $data;
        } catch (\RuntimeException $e) {
            throw new \RuntimeException('An error occurred while parsing the PDF: ' . $e->getMessage());
        }
    }
}
