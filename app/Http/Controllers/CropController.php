<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CropController extends Controller
{
    /**
     * Affiche toutes les cultures de l'utilisateur connecté avec pagination.
     */
    public function index(Request $request)
    {
        // Utilisez `paginate` sur la requête Eloquent
        $crops = Crop::where('user_id', auth()->id())->paginate(10); 
        
        return Inertia::render('Crops/Index', [
            'crops' => $crops,
        ]);
    }

    /**
     * Stocke une nouvelle culture.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);

        $crop = Crop::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('crops.index')->with('success', 'Culture ajoutée avec succès.');
    }

    /**
     * Met à jour une culture existante.
     */
    public function update(Request $request, Crop $crop)
    {
        $this->authorize('update', $crop);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'status' => 'required|string',
        ]);

        $crop->update([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
        ]);

        return redirect()->route('crops.index')->with('success', 'Culture mise à jour avec succès.');
    }

    /**
     * Supprime une culture.
     */
    public function destroy(Crop $crop)
    {
        $this->authorize('delete', $crop);

        $crop->delete();

        return redirect()->route('crops.index')->with('success', 'Culture supprimée avec succès.');
    }
}
