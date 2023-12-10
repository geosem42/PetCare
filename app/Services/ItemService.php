<?php

namespace App\Services;

use App\Models\Item;

class ItemService
{
  public function storeItem($data)
  {
    return Item::create($data);
  }

  public function updateItem($data, $id)
  {
    $item = Item::findOrFail($id);
    $item->update($data);
    return $item;
  }

  public function destroyItem($id)
  {
    $item = Item::findOrFail($id);
    $item->delete();
  }

  public function bulkDeleteItems($ids)
  {
    Item::destroy($ids);
  }

  public function fetchAllItems($page)
  {
    $perPage = 10;
    $items = Item::orderBy('created_at', 'DESC')->paginate($perPage, ['*'], 'page', $page);

    return [
      'items' => $items,
      'links' => $items->links(),
      'count' => Item::all()->count(),
      'meta' => [
        'currentPage' => $items->currentPage(),
        'lastPage' => $items->lastPage(),
        'totalItems' => $items->total(),
        'perPage' => $items->perPage(),
      ],
    ];
  }

  public function searchItems($keywords)
  {
    $items = Item::where('item_name', 'like', '%' . $keywords . '%')->get();
    return $items;
  }

  public function findItemBySlug($slug)
  {
    /* $id = (int) substr($slug, strrpos($slug, '-') + 1);
    \Log::info('Finding item by id: '. $id);
    return Item::findOrFail($id); */
    return Item::where('slug', $slug)->firstOrFail();
  }
}