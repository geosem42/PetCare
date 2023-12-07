<?php

namespace App\Services;

use App\Models\MedicalHistory;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedicalHistoryService
{
	public function storeHistory($petId, $histories)
	{
		$savedHistories = [];

		foreach ($histories as $history) {
			if (isset($history['id'])) {
				MedicalHistory::find($history['id'])->update($history);
			} else {
				$savedHistory = MedicalHistory::updateOrCreate(
					['id' => $history['id']],
					$history
				);

				$savedHistories[] = $savedHistory;
			}
		}

		return $savedHistories;
	}

	public function fetchHistories($petId)
	{
		$histories = MedicalHistory::where('pet_id', $petId)->get();

    return $histories;

	}

	public function destroyHistory($petId, $historyId)
	{
		$history = MedicalHistory::findOrFail($historyId);

		if ((int) $history->pet_id !== (int) $petId) {
			throw new \Exception('The medical history does not belong to the specified pet');
		}

		$history->delete();

		return [
			'message' => 'Medical History successfully deleted!',
			'status' => 200
		];
	}
}