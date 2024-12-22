<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntrieStoreRequest;
use App\Http\Requests\EntrieUpdateRequest;
use App\Http\Resources\EntrieResource;
use App\Models\Entrie;
use Illuminate\Http\Request;

class EntrieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Entrie::class);

        $user = $request->user();
        $entries = $user->entries()->paginate(10);

        return EntrieResource::collection($entries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntrieStoreRequest $request)
    {
        $this->authorize('create', Entrie::class);

        $user = $request->user();
        $validated = $request->validated();

        $entrie = Entrie::create([
            'food_name' => $validated['food_name'],
            'calories' => $validated['calories'],
            'meal_id' => $validated['meal_id'],
            'user_id' => $user->id
        ]);

        $entrie->save();

        return response()->json([
            'message' => 'Entrie created',
            'entrie' => new EntrieResource($entrie)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $entrie = Entrie::findOrFail($id);
        $this->authorize('view', $entrie);

        return response()->json(new EntrieResource($entrie), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EntrieUpdateRequest $request, string $id)
    {

        $validated = $request->validated();
        $entrie = Entrie::findOrFail($id);
        $this->authorize('update', $entrie);

        $entrie->food_name = $validated['food_name'];
        $entrie->calories = $validated['calories'];
        $entrie->meal_id = $validated['meal_id'];

        $entrie->save();

        return response()->json([
            'message' => 'entrie updated',
            'entrie' => new EntrieResource($entrie)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $entrie = Entrie::findOrFail($id);
        $this->authorize('delete', $entrie);

        $entrie->delete();

        return response()->json(['message' => 'entrie deleted'], 204);
    }
}
