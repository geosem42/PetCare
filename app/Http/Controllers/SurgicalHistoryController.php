<?php

namespace App\Http\Controllers;

use App\Http\Requests\Surgical\SurgicalHistoryRequest;
use App\Services\SurgicalHistoryService;

class SurgicalHistoryController extends Controller
{
	protected $surgicalHistoryService;

	public function __construct(SurgicalHistoryService $surgicalHistoryService)
	{
		$this->surgicalHistoryService = $surgicalHistoryService;
	}

	public function storeSurgery($petId, SurgicalHistoryRequest $request)
	{
		$surgeries = $this->surgicalHistoryService->storeSurgeries($petId, $request->surgeries);

		return response()->json([
			'message' => 'Surgical Histories successfully saved!',
			'surgeries' => $surgeries
		], 200);
	}

	public function fetchSurgeries($id)
	{
		$surgeries = $this->surgicalHistoryService->fetchSurgeries($id);
		return response()->json($surgeries);
	}

	public function destroySurgery($petId, $surgeryId)
	{
		$response = $this->surgicalHistoryService->destroySurgery($petId, $surgeryId);

		return response()->json([
			'message' => $response['message']
		], $response['status']);
	}
}