<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealRequest;
use App\Http\Resources\MealResource;
use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Meal::class);

        $user = $request->user();

        $meals = Meal::forUser($user)->paginate(10);

        return MealResource::collection($meals);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MealRequest $request)
    {
        $user = $request->user();
        $validated = $request->validated();

        $meal = Meal::create([
            'name' => $validated['name'],
            'entries_limit' => $validated['entries_limit'],
            'user_id' => $user->id
        ]);

        $meal->save();

        return response()->json([
            'message' => 'Meal created',
            'meal' => new MealResource($meal)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $meal = Meal::findOrFail($id);
        $this->authorize('view', $meal);

        return response()->json(new MealResource($meal), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MealRequest $request, string $id)
    {
        $validated = $request->validated();
        $meal = Meal::findOrFail($id);

        $this->authorize('update', $meal);
        $meal->name = $validated['name'];
        $meal->entries_limit = $validated['entries_limit'];
        $meal->save();

        return response()->json([
            'message' => 'Meal updated',
            'meal' => new MealResource($meal)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $meal = Meal::findOrFail($id);
        $this->authorize('delete', $meal);

        $meal->delete();

        return response()->json(['message' => 'Meal deleted'], 204);
    }
}
