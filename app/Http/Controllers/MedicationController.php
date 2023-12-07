<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicationRequest;
use App\Services\MedicationService;
use Illuminate\Http\Request;

class MedicationController extends Controller
{

	protected $medicationService;

	public function __construct(MedicationService $medicationService)
	{
		$this->medicationService = $medicationService;
	}

	public function storeMedication($petId, MedicationRequest $request)
	{
		$medications = $this->medicationService->storeMedication($petId, $request->medications);

		return response()->json([
			'message' => 'Medications successfully saved!',
			'medications' => $medications
		], 200);
	}

	public function fetchMedications($id, Request $request)
	{
		$medications = $this->medicationService->fetchMedications($id);

		return response()->json($medications);
	}

	public function destroyMedication($petId, $medicationId)
	{
		$response = $this->medicationService->destroyMedication($petId, $medicationId);

		return response()->json([
			'message' => $response['message']
		], $response['status']);
	}
}