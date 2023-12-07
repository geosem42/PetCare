<?php

namespace App\Services;

use App\Models\Pet;
use App\Models\Vaccination;

class VaccinationService
{
  public function storeVaccinations($petId, $vaccinations)
{
    $pet = Pet::findOrFail($petId);
    $savedVaccinations = [];

    foreach ($vaccinations as $vaccination) {
        if (isset($vaccination['id']) && $vaccination['id']) {
            $existingVaccination = Vaccination::find($vaccination['id']);
            if ($existingVaccination) {
                $existingVaccination->update($vaccination);
            }
        } else {
            // If the 'id' key is not set or is null, create a new Vaccination record
            $vaccination['pet_id'] = $petId; // Assuming 'pet_id' is a foreign key in the 'vaccinations' table
            $savedVaccination = Vaccination::create($vaccination);
            $savedVaccinations[] = $savedVaccination;
        }
    }
    return $savedVaccinations;
}

  public function fetchVaccinations($petId)
  {
      $vaccinations = Vaccination::where('pet_id', $petId)->get();

      return $vaccinations;
  }
  public function destroyVaccination($petId, $vaccinationId)
  {
    $pet = Pet::findOrFail($petId);
    $vaccination = Vaccination::findOrFail($vaccinationId);

    if ((int) $vaccination->pet_id !== (int) $pet->id) {
      throw new \Exception('The vaccination does not belong to the specified pet');
    }

    $vaccination->delete();

    return [
      'message' => 'Vaccination successfully deleted!',
      'status' => 200
    ];
  }
}