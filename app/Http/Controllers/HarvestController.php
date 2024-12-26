<?php


namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Harvest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HarvestController extends Controller
{
    public function index(Crop $crop)
    {
        $harvests = $crop->harvests()->paginate(10);
        return Inertia::render('Harvests/Index', [
            'harvests' => $harvests,
            'crop' => $crop,
        ]);
    }

    public function store(Request $request, Crop $crop)
    {
        $request->validate([
            'harvest_date' => 'required|date',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|string',
        ]);

        $crop->harvests()->create($request->all());

        return redirect()->back()->with('success', 'Récolte ajoutée avec succès.');
    }

    public function update(Request $request, Crop $crop, Harvest $harvest)
    {
        $request->validate([
            'harvest_date' => 'required|date',
            'quantity' => 'required|integer',
            'status' => 'required|string|max:255',
        ]);

        $harvest->update($request->all());

        return redirect()->route('harvests.index', $crop)->with('success', 'Récolte mise à jour avec succès !');
    }

    public function destroy(Crop $crop, Harvest $harvest)
    {
        $harvest->delete();

        return redirect()->route('harvests.index', $crop)->with('success', 'Récolte supprimée avec succès !');
    }
}
