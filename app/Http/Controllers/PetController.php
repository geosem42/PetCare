<?php

namespace App\Http\Controllers;

use App\Http\Requests\Pet\PetStoreRequest;
use App\Http\Requests\Pet\PetUpdateRequest;
use App\Http\Requests\Pet\PetBulkDeleteRequest;
use App\Models\Pet;
use App\Services\PetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PetController extends Controller
{
	protected $petService;

	public function __construct(PetService $petService)
	{
		$this->petService = $petService;
	}

	public function index(): Response
	{
		return Inertia::render('Pets/Index');
	}

	public function create(): Response
	{
		return Inertia::render('Pets/Create');
	}

	public function show($slug)
	{
		$pet = Pet::where('slug', $slug)->with('user', 'species', 'breed', 'vaccinations', 'medications', 'medicalHistory', 'surgicalHistory', 'galleries')->first();

		if ($pet->photo) {
			$pet->photo = url('/') . '/' . $pet->photo;
		}

		// Return the pet as a JSON response
		return Inertia::render('Pets/Show', [
			'pet' => $pet
		]);
	}

	public function store(PetStoreRequest $request): JsonResponse
	{
		\Log::info($request->all());
		$this->petService->createPet($request->validated());

		return response()->json([
			'message' => 'Pet successfully added!'
		], 201);
	}

	public function edit($slug): Response
	{
		$pet = Pet::where('slug', $slug)->with(['client', 'species', 'breed'])->firstOrFail();

		if ($pet->photo) {
			$pet->photo = url('/') . '/' . $pet->photo;
		}

		return Inertia::render('Pets/Edit', [
			'pet' => $pet
		]);
	}

	public function update($id, PetUpdateRequest $request): JsonResponse
	{
		$this->petService->updatePet($id, $request->validated());

		return response()->json([
			'message' => 'Pet successfully updated!'
		], 200);
	}

	public function destroy($id): JsonResponse
	{
		$this->petService->deletePet($id);

		return response()->json([
			'message' => 'Pet successfully deleted!'
		], 200);
	}

	public function bulkDelete(PetBulkDeleteRequest $request): JsonResponse
	{
		$this->petService->bulkDeletePets($request->validated()['selectedIds']);
		return response()->json(['success' => 'Selected pets deleted successfully'], 201);
	}

	public function fetchAllPets(Request $request): JsonResponse
	{
		$pets = $this->petService->fetchAllPets($request->query('page', 1));
		return response()->json($pets, 201);
	}

	public function fetchAllSpecies(Request $request): JsonResponse
	{
		$species = $this->petService->fetchAllSpecies();
		return response()->json($species, 201);
	}

	public function fetchAllBreeds(Request $request): JsonResponse
	{
		$breeds = $this->petService->fetchAllBreeds($request->species_id);
		return response()->json($breeds);
	}

	public function search(Request $request): JsonResponse
	{
		$pets = $this->petService->search($request->keywords);
		return response()->json($pets, 201);
	}

	public function searchSpecies(Request $request): JsonResponse
	{
		$species = $this->petService->searchSpecies($request->name);
		return response()->json($species);
	}

	public function searchBreeds(Request $request): JsonResponse
	{
		$breeds = $this->petService->searchBreeds($request->species_id);
		return response()->json($breeds, 201);
	}

	public function fetchAllClients(): JsonResponse
	{
		$clients = $this->petService->fetchAllClients();
		return response()->json($clients);
	}

	public function searchClients(Request $request): JsonResponse
	{
		$search = $request->input('name');
		$clients = $this->petService->searchClients($search);
		return response()->json($clients);
	}
}