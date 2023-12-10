<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Pet;
use App\Models\User;
use App\Models\Client;
use App\Http\Requests\Pet\PetStoreRequest;
use App\Http\Requests\Pet\PetUpdateRequest;
use App\Http\Requests\Pet\PetBulkDeleteRequest;

class PetTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->get('/pets');
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $response = $this->get('/pets/create');
        $response->assertStatus(200);
    }

    public function testShow()
    {
        $pet = Pet::factory()->create();
        $response = $this->get('/pets/' . $pet->slug . '/show');
        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $pet = Pet::factory()->create();
        $response = $this->get('/pets/' . $pet->slug . '/edit');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $petData = ['name' => 'Test Pet', 'species_id' => 1, 'client_id' => 1]; // Add more fields as necessary
        $request = new PetStoreRequest();
        $request->setContainer(app());
        $request->initialize([], $petData);
        $request->setMethod('POST');
        $request->setValidator(app()->get('validator')->make($petData, $request->rules()));
        $response = $this->post('/pets/store', $request->validated());
        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $pet = Pet::factory()->create();
        $petData = ['name' => 'Updated Pet', 'species_id' => 1, 'client_id' => 1]; // Add more fields as necessary
        $request = new PetUpdateRequest();
        $request->setContainer(app());
        $request->initialize([], $petData);
        $request->setMethod('POST');
        $request->setValidator(app()->get('validator')->make($petData, $request->rules()));
        $response = $this->post('/pets/' . $pet->id, $request->validated());
        $response->assertStatus(200);
    }
    
    public function testDestroy()
    {
        $pet = Pet::factory()->create();
        $response = $this->delete('/pets/' . $pet->id);
        $response->assertStatus(200);
    }

    public function testBulkDelete()
    {
        $pets = Pet::factory()->count(3)->create();
        $ids = $pets->pluck('id')->toArray();
        $request = new PetBulkDeleteRequest();
        $request->setContainer(app());
        $request->initialize([], ['selectedIds' => $ids]);
        $request->setMethod('DELETE');
        $request->setValidator(app()->get('validator')->make(['selectedIds' => $ids], $request->rules()));
        $response = $this->delete('/pets/bulk-delete/selected', $request->validated());
        $response->assertStatus(201);
    }
}

