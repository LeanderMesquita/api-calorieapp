<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('admin')) {
            return response()->json([
                'error' => 'Not allowed'
            ], 403);
        }

        $this->authorize('viewAny', User::class);

        $users = User::with('role')->paginate(10);
        return UserResource::collection($users);
    }

   

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $user = User::with('role')->findOrFail($id);
        $this->authorize('view', $user);

        return response()->json(new UserResource($user), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {

        $validated = $request->validated();
        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $user->fill($validated);
        $user->save();

        return response()->json([
            'message' => 'User updated',
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if (!$request->user()->tokenCan('admin')) {
            return response()->json([
                'error' => 'Not allowed'
            ], 403);
        }

        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'User deleted'
        ], 204);
    }
}
