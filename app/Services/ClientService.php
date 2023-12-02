<?php

namespace App\Services;

use App\Models\Client;

class ClientService
{
  public function storeClient($data)
  {
    $user = Client::create($data);
    return $user;
  }

  public function updateClient($data, $id)
  {
    $client = Client::findOrFail($id);
    $client->update($data);
    return $client;
  }

  public function destroyClient($id)
  {
    $client = Client::findOrFail($id);
    $client->delete();
  }

  public function bulkDeleteClients($ids)
  {
    Client::destroy($ids);
  }

  public function fetchClientById($id)
  {
      $client = Client::findOrFail($id);

      return $client;
  }

  public function fetchAllClients($page)
  {
    $perPage = 10;
    $clients = Client::orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);

    return [
      
      'clients' => $clients,
      'links' => $clients->links(),
      'count' => Client::all()->count(),
      'meta' => [
        'currentPage' => $clients->currentPage(),
        'lastPage' => $clients->lastPage(),
        'totalItems' => $clients->total(),
        'perPage' => $clients->perPage(),
      ],
    ];
  }

  public function searchClients($keywords)
  {
    $clients = Client::where('name', 'like', '%' . $keywords . '%')->get();
    return $clients;
  }

  public function findClientBySlug($slug)
  {
    $id = (int) substr($slug, strrpos($slug, '-') + 1);
    return Client::with('pets.species', 'pets.breed')->findOrFail($id);
  }
}