<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\SurgicalHistory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SurgicalHistoryService
{
  public function storeSurgeries($petId, $surgeries)
  {
    $pet = Pet::findOrFail($petId);
    $savedSurgeries = [];

    foreach ($surgeries as $surgery) {
      if (isset($surgery['id'])) {
        SurgicalHistory::find($surgery['id'])->update($surgery);
      } else {
        $savedSurgery = SurgicalHistory::updateOrCreate(
          ['id' => $surgery['id']],
          $surgery
        );
        $savedSurgeries[] = $savedSurgery;
      }
    }
    return $savedSurgeries;
  }

  public function fetchSurgeries($petId)
  {
    $surgeries = SurgicalHistory::where('pet_id', $petId)->get();

    return $surgeries;
  }

  public function destroySurgery($petId, $surgeryId)
  {
    $pet = Pet::findOrFail($petId);
    $surgery = SurgicalHistory::findOrFail($surgeryId);

    if ((int) $surgery->pet_id !== (int) $pet->id) {
      throw new \Exception('The surgical history does not belong to the specified pet');
    }

    $surgery->delete();

    return [
      'message' => 'Surgical History successfully deleted!',
      'status' => 200
    ];
  }
}