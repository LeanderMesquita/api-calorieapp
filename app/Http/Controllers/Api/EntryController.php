<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EntryStoreRequest;
use App\Http\Requests\EntryUpdateRequest;
use App\Http\Resources\EntryResource;
use App\Models\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Entry::class);

        $user = $request->user();

        $entries = $user->isAdmin()
            ? Entry::paginate(10)
            : $user->entries()->paginate(10);

        return EntryResource::collection($entries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntryStoreRequest $request)
    {

        $user = $request->user();
        $validated = $request->validated();

        $entry = Entry::create([
            'food_name' => $validated['food_name'],
            'calories' => $validated['calories'],
            'meal_id' => $validated['meal_id'],
            'user_id' => $user->id
        ]);

        $this->authorize('create', $entry);

        $entry->save();

        return response()->json([
            'message' => 'Entry created',
            'entry' => new EntryResource($entry)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $entry = Entry::findOrFail($id);
        $this->authorize('view', $entry);

        return response()->json(new EntryResource($entry), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EntryUpdateRequest $request, string $id)
    {

        $validated = $request->validated();
        $entry = Entry::findOrFail($id);
        $this->authorize('update', $entry);

        $entry->food_name = $validated['food_name'];
        $entry->calories = $validated['calories'];
        $entry->meal_id = $validated['meal_id'];

        $entry->save();

        return response()->json([
            'message' => 'entry updated',
            'entry' => new EntryResource($entry)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $entry = Entry::findOrFail($id);
        $this->authorize('delete', $entry);

        $entry->delete();

        return response()->json(['message' => 'entry deleted'], 204);
    }
}
