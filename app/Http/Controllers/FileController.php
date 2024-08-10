<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download($filename)
    {
        // Check if the file exists in the storage
        $filePath = 'public/counseling_files/' . $filename;
        // dd($filename);
        $storage = Storage::allFiles();
        // dd($storage);
        if (!Storage::exists($filePath)) {
            return response()->json(['message' => 'File not found.'], 404);
        }

        // Return the file as a streamed response
        return Storage::download($filePath);
    }
    /**
     * Display the attachment file based on the provided path.
     */
    public function showAttachment($path)
    {
        try {
            $filePath = Storage::path('public/attachments/' . $path);
            return response()->file($filePath);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve attachment',$e], 500);
        }
    }
}
