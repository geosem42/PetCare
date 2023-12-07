<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vaccination\VaccinationRequest;
use App\Services\VaccinationService;

class VaccinationController extends Controller
{
	protected $vaccinationService;

	public function __construct(VaccinationService $vaccinationService)
	{
		$this->vaccinationService = $vaccinationService;
	}

	public function storeVaccination($petId, VaccinationRequest $request)
	{
		$vaccinations = $this->vaccinationService->storeVaccinations($petId, $request->vaccinations);
		return response()->json([
			'message' => 'Vaccinations successfully saved!',
			'vaccinations' => $vaccinations
		], 200);
	}

	public function fetchVaccinations($id)
	{
		$vaccinations = $this->vaccinationService->fetchVaccinations($id);
		return response()->json($vaccinations);
	}

	public function destroyVaccination($petId, $vaccinationId)
	{
		$response = $this->vaccinationService->destroyVaccination($petId, $vaccinationId);

		return response()->json([
			'message' => $response['message']
		], $response['status']);
	}
}