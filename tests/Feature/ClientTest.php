<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\User;
use App\Http\Requests\Client\ClientStoreRequest;
use App\Http\Requests\Client\ClientUpdateRequest;
use App\Http\Requests\Client\ClientBulkDeleteRequest;
use App\Http\Requests\Client\ClientFetchAllRequest;
use App\Http\Requests\Client\ClientSearchRequest;

class ClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->get('/clients');
        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $response = $this->get('/clients/create');
        $response->assertStatus(200);
    }

    public function testShow()
    {
        $client = Client::factory()->create();
        $response = $this->get('/clients/' . $client->slug . '/show');
        $response->assertStatus(200);
    }

    public function testEdit()
    {
        $client = Client::factory()->create();
        $response = $this->get('/clients/' . $client->slug . '/edit');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        $clientData = ['name' => 'Test Client', 'email' => 'test@example.com', 'phone_number' => '81654987']; // Add more fields as necessary
        $request = new ClientStoreRequest();
        $request->setContainer(app());
        $request->initialize([], $clientData);
        $request->setMethod('POST');
        $request->setValidator(app()->get('validator')->make($clientData, $request->rules()));
        $response = $this->post('/clients/store', $request->validated());
        $response->assertStatus(201);
    }

    public function testUpdate()
    {
        $client = Client::factory()->create();
        $clientData = ['name' => 'Updated Client', 'email' => 'updated@example.com', 'phone_number' => '81654987']; // Add more fields as necessary
        $request = new ClientUpdateRequest();
        $request->setContainer(app());
        $request->initialize([], $clientData);
        $request->setMethod('PUT');
        $request->setValidator(app()->get('validator')->make($clientData, $request->rules()));
        $response = $this->put('/clients/' . $client->id, $request->validated());
        $response->assertStatus(201);
    }

    public function testDestroy()
    {
        $client = Client::factory()->create();
        $response = $this->delete('/clients/' . $client->id);
        $response->assertStatus(201);
    }

    public function testBulkDelete()
    {
        $clients = Client::factory()->count(3)->create();
        $ids = $clients->pluck('id')->toArray();
        $request = new ClientBulkDeleteRequest();
        $request->setContainer(app());
        $request->initialize([], ['selectedIds' => $ids]);
        $request->setMethod('DELETE');
        $request->setValidator(app()->get('validator')->make(['selectedIds' => $ids], $request->rules()));
        $response = $this->delete('/clients/bulk-delete/selected', $request->validated());
        $response->assertStatus(201);
    }

    public function testFetchAllClients()
    {
        $request = new ClientFetchAllRequest(['page' => 1]);
        $response = $this->get('/clients/fetchAllClients', $request->query());
        $response->assertStatus(201);
    }

    public function testSearch()
    {
        $keywords = 'test';
        $request = new ClientSearchRequest();
        $request->setContainer(app());
        $request->initialize([], ['keywords' => $keywords]);
        $request->setMethod('POST');
        $request->setValidator(app()->get('validator')->make(['keywords' => $keywords], $request->rules()));
        $response = $this->post('/clients/search', $request->validated());
        $response->assertStatus(201);
    }
}
