<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class FarmerController extends Controller
{
    public function index()
    {
        return Inertia::render('Farmer/Dashboard', [
            'message' => 'Bienvenue Farmer',
        ]);
    }
}
