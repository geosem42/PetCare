<?php

namespace App\Services;

use App\Models\Medication;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicationService
{
	public function storeMedication($petId, $medications)
	{
		$savedMedications = [];

		foreach ($medications as $medication) {
			if (isset($medication['id'])) {
				Medication::find($medication['id'])->update($medication);
			} else {
				$savedMedication = Medication::updateOrCreate(
					['id' => $medication['id']],
					$medication
				);

				$savedMedications[] = $savedMedication;
			}
		}

		return $savedMedications;
	}

	public function fetchMedications($petId)
	{
		$medications = Medication::where('pet_id', $petId)->get();

		return $medications;

	}

	public function destroyMedication($petId, $medicationId)
	{
		$medication = Medication::findOrFail($medicationId);

		if ((int) $medication->pet_id !== (int) $petId) {
			throw new \Exception('The medication does not belong to the specified pet');
		}

		$medication->delete();

		return [
			'message' => 'Medication successfully deleted!',
			'status' => 200
		];
	}
}