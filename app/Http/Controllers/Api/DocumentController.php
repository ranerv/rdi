<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Project;
use App\Models\UploadedDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class DocumentController
{
    public function index(Project $project): JsonResponse
    {
        $documents = $project->uploadedDocuments()->with('uploader')->paginate(15);

        return response()->json([
            'data' => DocumentResource::collection($documents->items()),
            'pagination' => [
                'total' => $documents->total(),
                'per_page' => $documents->perPage(),
                'current_page' => $documents->currentPage(),
                'last_page' => $documents->lastPage(),
            ],
            'message' => 'Documents retrieved',
            'status' => true,
        ]);
    }

    public function store(StoreDocumentRequest $request, Project $project): JsonResponse
    {
        $file = $request->file('file');
        $path = $file->store('documents/' . $project->id, 'public');

        $document = $project->uploadedDocuments()->create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'document_type' => $request->document_type,
            'uploaded_by' => auth()->id(),
        ]);

        return response()->json([
            'data' => new DocumentResource($document),
            'message' => 'Document uploaded successfully',
            'status' => true,
        ], 201);
    }

    public function destroy(UploadedDocument $document): JsonResponse
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();

        return response()->json([
            'data' => null,
            'message' => 'Document deleted successfully',
            'status' => true,
        ]);
    }
}
