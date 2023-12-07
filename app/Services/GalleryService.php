<?php

namespace App\Services;

use App\Models\Gallery;
use App\Models\Pet;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GalleryService
{
  public function storeGallery($petId, $files)
  {
    $pet = Pet::findOrFail($petId);

    $savedFiles = [];

    foreach ($files as $index => $file) {
      $newImageName = time() . '-' . $pet->id . '-' . $index . '.' . $file->extension();

      $directory = public_path('storage/photos/' . $pet->id);
      if (!file_exists($directory)) {
        mkdir($directory, 0777, true);
      }

      $file->move($directory, $newImageName);

      $photoPath = 'storage/photos/' . $pet->id . '/' . $newImageName;

      $gallery = new Gallery;
      $gallery->pet_id = $pet->id;
      $gallery->path = $photoPath;
      $gallery->save();

      $savedFiles[] = $gallery;
    }

    return $savedFiles;
  }

  public function fetchAllImages($petId)
  {
    $galleries = Gallery::where('pet_id', $petId)->get();

    // Map each gallery item to add the asset URL to the path
    $galleries->map(function ($gallery) {
      $gallery->path = asset($gallery->path);
      return $gallery;
    });

    return $galleries;
  }

  public function destroy($petId, $galleryId)
  {
    $gallery = Gallery::findOrFail($galleryId);

    if ((int) $gallery->pet_id !== (int) $petId) {
      throw new \Exception('The image does not belong to the specified pet');
    }

    $gallery->delete();

    return [
      'message' => 'Image successfully deleted!',
      'status' => 200
    ];
  }
}