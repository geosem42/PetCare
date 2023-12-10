<?php

namespace App\Http\Controllers;

use App\Http\Requests\Item\ItemStoreRequest;
use App\Http\Requests\Item\ItemUpdateRequest;
use App\Http\Requests\Item\ItemFetchAllRequest;
use App\Http\Requests\Item\ItemBulkDeleteRequest;
use App\Http\Requests\Item\ItemSearchRequest;
use App\Services\ItemService;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class ItemController extends Controller
{
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Items/Index');
    }

    public function create(): \Inertia\Response
    {
        return Inertia::render('Items/Create');
    }

    public function show($slug): \Inertia\Response
    {
        $item = $this->itemService->findItemBySlug($slug);
        return Inertia::render('Items/Show', ['item' => $item]);
    }

    public function edit($slug)
    {
        $item = $this->itemService->findItemBySlug($slug); // the problem is here
        return Inertia::render('Items/Edit', ['item' => $item]);
    }

    public function store(ItemStoreRequest $request): JsonResponse
    {
        $item = $this->itemService->storeItem($request->validated());
        return response()->json(['message' => 'Item successfully added!'], 201);
    }

    public function update(ItemUpdateRequest $request, $id): JsonResponse
    {
        $item = $this->itemService->updateItem($request->validated(), $id);
        return response()->json(['message' => 'Item updated successfully'], 201);
    }

    public function destroy($id): JsonResponse
    {
        $this->itemService->destroyItem($id);
        return response()->json(['success' => 'Item deleted successfully'], 201);
    }

    public function bulkDelete(ItemBulkDeleteRequest $request): JsonResponse
    {
        $this->itemService->bulkDeleteItems($request->validated()['selectedIds']);
        return response()->json(['success' => 'Selected items deleted successfully'], 201);
    }

    public function fetchAllItems(ItemFetchAllRequest $request): JsonResponse
    {
        $items = $this->itemService->fetchAllItems($request->query('page', 1));
        return response()->json($items, 201);
    }

    public function search(ItemSearchRequest $request): JsonResponse
    {
        $items = $this->itemService->searchItems($request->validated()['keywords']);
        return response()->json($items, 201);
    }
}
