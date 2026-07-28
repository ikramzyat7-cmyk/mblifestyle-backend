<?php
namespace App\Http\Controllers;
use App\Models\DeliveryCity;
use Illuminate\Http\Request;

class DeliveryCityController extends Controller
{
    public function index()
    {
        return DeliveryCity::where('is_active', true)->orderBy('order')->orderBy('name')->get();
    }

    public function adminIndex()
    {
        return DeliveryCity::orderBy('order')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'order' => 'nullable|integer',
        ]);
        return DeliveryCity::create($validated);
    }

    public function update(Request $request, DeliveryCity $deliveryCity)
    {
        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'price'     => 'sometimes|numeric|min:0',
            'is_active' => 'sometimes|boolean',
            'order'     => 'sometimes|integer',
        ]);
        $deliveryCity->update($validated);
        return $deliveryCity;
    }

    public function destroy(DeliveryCity $deliveryCity)
    {
        $deliveryCity->delete();
        return response()->json(['message' => 'Ville supprimée']);
    }
}
