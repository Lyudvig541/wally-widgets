<?php

namespace App\Http\Controllers;

use App\Models\Packs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PacksController extends Controller
{
    public function index()
    {
        $packs = Packs::orderBy('count_of_widgets')->get();
        return response()->json(['message' => 'Packs get', 'data' => $packs]);
    }

    /**
     * Create a new pack.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'count_of_widgets' => 'required|integer',
        ]);

        $pack = Packs::create($validated);

        return response()->json(['message' => 'Pack created successfully', 'data' => $pack], 201);
    }

    /*
    * Get the pack details by ID.
    *
    * @param  int  $id
    * @return \Illuminate\Http\JsonResponse
    */
    public function edit($id)
    {
        $pack = Packs::find($id);

        if (!$pack) {
            return response()->json(['error' => 'Pack not found'], 404);
        }

        return response()->json(['message' => 'Pack found', 'data' => $pack]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:packs,id',
            'name' => 'required',
            'count_of_widgets' => 'required',
        ]);

        $pack = Packs::find($request->id);
        $pack->name = $request->name;
        $pack->count_of_widgets = $request->count_of_widgets;

        // Save the changes to the database
        $pack->save();

        return response()->json([
            'message' => 'Pack updated successfully!',
        ], 200);
    }

    /**
     * Delete a pack by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        // Find the pack by ID
        $pack = Packs::find($id);

        if (!$pack) {
            return response()->json(['error' => 'Pack not found'], 404);
        }

        // Delete the pack
        $pack->delete();

        return response()->json(['message' => 'Pack deleted successfully']);
    }

    public function calculateOrder($orderQuantity, $packSizes)
    {
        rsort($packSizes);
        $result = [];
        foreach ($packSizes as $packSize) {
            if ($orderQuantity <= 0) {
                break;
            }
            $numPacks = intdiv($orderQuantity, $packSize);
            if ($numPacks > 0) {
                $result[$packSize] = $numPacks;
                $orderQuantity -= $numPacks * $packSize;
            }
        }
        if ($orderQuantity > 0) {
            $smallestPack = min($packSizes);
            $result[$smallestPack] = ($result[$smallestPack] ?? 0) + 1;
        }
        return $result;
    }


    public function order(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'count' => 'required|integer|min:1',
        ]);
        if ($validated->fails()) {
            return response()->json([
                'message' => 'please give a valid count',
            ], 500);
        }

        $pack = Packs::where('count_of_widgets', '=', $request->count)->get();
        if ($pack->isNotEmpty()) {
            return response()->json([
                'result' => [$pack[0]->count_of_widgets => 1]
            ], 200);
        }

        $bigCount = Packs::where('count_of_widgets', '>', $request->count)->first();


        $packSizes = Packs::where('count_of_widgets', '<', $request->count)->pluck('count_of_widgets');
        $arr = [...$packSizes];
        $results = $this->calculateOrder($request->count, $arr);
        $sum = 0;
        foreach ($results as $widCount => $count) {
            $sum += $widCount * $count;
        }
        if ($bigCount && $sum >= $bigCount->count_of_widgets) {
            return response()->json([
                'result' => [$bigCount->count_of_widgets => 1]
            ], 200);
        }
        return response()->json([
            'result' => $results
        ], 200);

    }
}
