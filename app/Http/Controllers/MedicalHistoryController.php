<?php

namespace App\Http\Controllers;

use App\Http\Requests\History\MedicalHistoryRequest;
use App\Services\MedicalHistoryService;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
	protected $medicalHistoryService;

	public function __construct(MedicalHistoryService $medicalHistoryService)
	{
		$this->medicalHistoryService = $medicalHistoryService;
	}

	public function storeHistory($petId, MedicalHistoryRequest $request)
	{
		$histories = $this->medicalHistoryService->storeHistory($petId, $request->histories);

		return response()->json([
			'message' => 'Medical Histories successfully saved!',
			'histories' => $histories
		], 200);
	}

	public function fetchHistories($id, Request $request)
	{
		$histories = $this->medicalHistoryService->fetchHistories($id);

		return response()->json($histories);
	}

	public function destroyHistory($petId, $historyId)
	{
		$response = $this->medicalHistoryService->destroyHistory($petId, $historyId);

		return response()->json([
			'message' => $response['message']
		], $response['status']);
	}
}