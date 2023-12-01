<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function index() {
        return Inertia::render('Inventory/Index');
    }
}
