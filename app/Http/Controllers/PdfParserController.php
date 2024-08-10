<?php

namespace App\Http\Controllers;

use App\Services\PdfParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Spatie\PdfToText\Pdf;

class PdfParserController extends Controller
{
    protected $pdfParserService;

    public function __construct(PdfParserService $pdfParserService)
    {
        $this->pdfParserService = $pdfParserService;
    }

    public function parse(Request $request)
    {
        // dd($request);
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf',
        ]);

        $filename = 'custom_name_' . time() . '.pdf';
        $pdfPath = $request->file('pdf_file')->storeAs('pdfs', $filename);

        $path = Storage::path($pdfPath);


        try {
            // Pass the path to the PdfParserService
            $parsedData = $this->pdfParserService->parse($path);

            return response()->json(['message' => 'PDF parsed successfully', 'data' => $parsedData], 200);

        } catch (\RuntimeException $e) {
            // Return a detailed error message
            return response()->json(['message' => $e->getMessage(), 'error' => $e->getTraceAsString()], 500);
        }
    }
}
