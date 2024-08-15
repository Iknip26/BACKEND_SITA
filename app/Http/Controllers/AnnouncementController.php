<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AnnouncementResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $announcements = Announcement::paginate(5);
            return response()->json([
            "message" => "data retrieved successfully",
            "data" => AnnouncementResource::collection($announcements),
            'meta' => [
                'total' => $announcements->total(),
                'per_page' => $announcements->perPage(),
                'current_page' => $announcements->currentPage(),
                'last_page' => $announcements->lastPage(),
                'next_page' => $announcements->nextPageUrl(),
                'previous_page' => $announcements->previousPageUrl()
            ]
        ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve announcements'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'detail' => 'required|string',
                'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:51200',
            ]);

            if ($request->hasFile('attachment')) {
                $titleSlug = Str::slug($request->title);
                $date = now()->format('Y-m-d');
                $fileExtension = $request->file('attachment')->getClientOriginalExtension();
                $fileName = "{$titleSlug}_{$date}.{$fileExtension}";
                $filePath = $request->file('attachment')->storeAs('attachments', $fileName, 'public');
                $validatedData['attachment'] = $fileName;
            }

            $announcement = Announcement::create($validatedData);

            return response()->json($announcement, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create announcement'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            return response()->json($announcement, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Announcement not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve announcement'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'detail' => 'required|string',
                'attachment' => 'nullable|file|mimes:jpg,png,pdf|max:51200',
            ]);

            $announcement = Announcement::findOrFail($id);

            if ($request->hasFile('attachment')) {
                $titleSlug = Str::slug($request->title);
                $date = now()->format('Y-m-d');
                $fileExtension = $request->file('attachment')->getClientOriginalExtension();
                $fileName = "{$titleSlug}_{$date}.{$fileExtension}";
                $filePath = $request->file('attachment')->storeAs('attachments', $fileName, 'public');
                $validatedData['attachment'] = $fileName;
            }

            $announcement->update($validatedData);

            return response()->json($announcement, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Announcement not found'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update announcement'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();

            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Announcement not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete announcement'], 500);
        }
    }
}
