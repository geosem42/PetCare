<?php

namespace App\Http\Controllers;

use App\Http\Requests\Gallery\GalleryRequest;
use App\Services\GalleryService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected $galleryService;

    public function __construct(GalleryService $galleryService)
    {
        $this->galleryService = $galleryService;
    }

    public function storeGallery($petId, GalleryRequest $request)
    {
        $files = $this->galleryService->storeGallery($petId, $request->allFiles()['files']);

        return response()->json([
            'message' => 'Photos uploaded successfully',
            'files' => $files
        ], 200);
    }

    public function fetchAllImages($id, Request $request)
    {
        $files = $this->galleryService->fetchAllImages($id);

        return response()->json($files);
    }

    public function destroy($petId, $galleryId)
    {
        $response = $this->galleryService->destroy($petId, $galleryId);

        return response()->json([
            'message' => $response['message']
        ], $response['status']);
    }
}