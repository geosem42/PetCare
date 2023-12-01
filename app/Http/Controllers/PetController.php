<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PetController extends Controller
{
    public function index() {
        return Inertia::render('Pets/Index');
    }
}
