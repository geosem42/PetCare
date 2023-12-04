<?php

namespace App\Services;

use App\Models\Breed;
use App\Models\Pet;
use App\Models\Species;
use App\Models\User;
Use App\Models\Client;

class PetService
{
    public function createPet(array $data): void
    {
        \Log::info('Create Pet', $data);
        $pet = Pet::create($data);

        if (array_key_exists('photo', $data) && $data['photo']->isValid()) {
            $this->handlePhotoUpload($pet, $data['photo']);
        }
    }

    public function updatePet($id, array $data): void
    {
        $pet = Pet::findOrFail($id);
        $pet->update($data);

        if (array_key_exists('photo', $data) && $data['photo']->isValid()) {
            $this->handlePhotoUpload($pet, $data['photo']);
        }
    }

    public function deletePet($id): void
    {
        $pet = Pet::findOrFail($id);
        $pet->delete();
    }

    public function bulkDeletePets($ids)
    {
      Pet::destroy($ids);
    }

    protected function handlePhotoUpload(Pet $pet, $photo): void
    {
        $petId = $pet->id;
        $filename = uniqid() . '.' . $photo->getClientOriginalExtension();
        $directory = public_path('storage/images/pets/' . $petId);

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $photo->move($directory, $filename);
        $photoPath = 'storage/images/pets/' . $petId . '/' . $filename;

        $pet->photo = $photoPath;
        $pet->save();
    }

    public function fetchAllPets($page)
    {
        $perPage = 10;
        $pets = Pet::with('species', 'breed')->orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);

        return [
        
        'pets' => $pets,
        'links' => $pets->links(),
        'count' => Pet::all()->count(),
        'meta' => [
            'currentPage' => $pets->currentPage(),
            'lastPage' => $pets->lastPage(),
            'totalItems' => $pets->total(),
            'perPage' => $pets->perPage(),
        ],
        ];
    }

    public function fetchAllSpecies()
    {
        return Species::paginate(10)->all();
    }

    public function fetchAllBreeds($speciesId)
    {
        return Breed::where('species_id', $speciesId)->get();
    }

    public function search($keywords)
    {
        return Pet::where('name', 'like', '%' . $keywords . '%')->with('user', 'species', 'breed')->get();
    }

    public function searchSpecies($name)
    {
        return Species::where('name', 'like', '%' . $name . '%')->get(['id', 'name']);
    }

    public function searchBreeds($speciesId)
    {
        return Breed::where('species_id', $speciesId)->get();
    }

    public function fetchAllClients()
    {
        return Client::take(10)->get(['id', 'name']);
    }

    public function searchClients($name)
    {
        return Client::where('name', 'like', "%$name%")->get(['id', 'name']);
    }
}
